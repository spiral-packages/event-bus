<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Spiral\Attributes\ReaderInterface;
use Spiral\EventBus\Attribute\Listener;
use Spiral\EventBus\Exception\InvalidListenerException;
use Spiral\Tokenizer\ClassesInterface;

final class ListenersLocator implements ListenersLocatorInterface
{
    public function __construct(
        private ClassesInterface $classes,
        private ReaderInterface $reader
    ) {
    }

    public function getListeners(): array
    {
        $listen = [];

        foreach ($this->classes->getClasses() as $class) {
            foreach ($class->getMethods() as $method) {
                if ($this->reader->firstFunctionMetadata($method, Listener::class)) {
                    if (! $method->isPublic()) {
                        throw new InvalidListenerException(
                            \sprintf(
                                'Listener method %s:%s should be public.',
                                $method->getDeclaringClass()->getName(),
                                $method->getName()
                            )
                        );
                    }

                    foreach ($this->processListenerAttributes($method) as $event => $listener) {
                        $listen[$event][] = $listener;
                    }
                }
            }
        }

        return $listen;
    }

    private function processListenerAttributes(\ReflectionMethod $method): iterable
    {
        foreach ($method->getParameters() as $parameter) {
            if (! $parameter->hasType()) {
                continue;
            }

            /** @var \ReflectionNamedType $type */
            $type = $parameter->getType();

            if ($type instanceof \ReflectionUnionType) {
                foreach ($type->getTypes() as $t) {
                    if (class_exists($t->getName())) {
                        yield $t->getName() => [$method->getDeclaringClass()->getName(), $method->getName()];
                    }
                }
            } elseif (class_exists($type->getName())) {
                yield $type->getName() => [$method->getDeclaringClass()->getName(), $method->getName()];
            }
        }
    }
}
