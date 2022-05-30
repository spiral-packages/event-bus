<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests;

use Mockery\MockInterface;
use Spiral\Attributes\AttributeReader;
use Spiral\EventBus\Attribute\Listener;
use Spiral\EventBus\Exception\InvalidListenerException;
use Spiral\EventBus\ListenersLocator;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes;
use Spiral\EventBus\Tests\App\Listener\QueueableListener;
use Spiral\EventBus\Tests\App\Listener\SimpleListener;
use Spiral\Tokenizer\ScopedClassesInterface;

final class ListenersLocatorTest extends TestCase
{
    private ListenersLocator $locator;
    private MockInterface|ScopedClassesInterface $classes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->locator = new ListenersLocator(
            $this->classes = $this->mockContainer(ScopedClassesInterface::class),
            new AttributeReader(),
        );
    }

    public function testListenersWithAttributesShouldBeFound(): void
    {
        $this->classes->shouldReceive('getScopedClasses')->once()->andReturn([
            new \ReflectionClass(ListenerWithAttributes::class),
            new \ReflectionClass(QueueableListener::class),
            new \ReflectionClass(SimpleListener::class),
        ]);

        $this->assertSame([
            SimpleEvent::class => [
                'Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes::methodA' => [
                    ListenerWithAttributes::class,
                    'methodA',
                ],
                'Spiral\EventBus\Tests\App\Listener\QueueableListener::handle' => [
                    QueueableListener::class,
                    'handle',
                ],
            ],
            SimpleAnotherEvent::class => [
                'Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes::methodB' => [
                    ListenerWithAttributes::class,
                    'methodB',
                ],
            ],
        ], $this->locator->getListeners());
    }

    public function testListenerWithProtectedHandlerMethodShouldThrowAnException(): void
    {
        $class = new \ReflectionClass(
            new class {

                #[Listener]
                protected function handle(SimpleEvent $event)
                {
                }
            }
        );


        $this->expectException(InvalidListenerException::class);
        $this->expectErrorMessage(\sprintf('Listener method %s:handle should be public.', $class->getName()));

        $this->classes->shouldReceive('getScopedClasses')->once()->andReturn([$class]);

        $this->locator->getListeners();
    }

    public function testListenersShouldBeRegisteredOnlyOnce(): void
    {
        $this->classes->shouldReceive('getScopedClasses')->once()->andReturn([
            new \ReflectionClass(ListenerWithAttributes::class),
            new \ReflectionClass(ListenerWithAttributes::class),
        ]);

        $this->assertSame([
            SimpleEvent::class => [
                'Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes::methodA' => [
                    ListenerWithAttributes::class,
                    'methodA',
                ],
            ],
            SimpleAnotherEvent::class => [
                'Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes::methodB' => [
                    ListenerWithAttributes::class,
                    'methodB',
                ],
            ],
        ], $this->locator->getListeners());
    }
}
