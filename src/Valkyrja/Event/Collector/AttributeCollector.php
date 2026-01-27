<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Event\Collector;

use Override;
use ReflectionException;
use ReflectionMethod;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Event\Attribute\Listener as Attribute;
use Valkyrja\Event\Collector\Contract\CollectorContract as Contract;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

class AttributeCollector implements Contract
{
    public function __construct(
        protected CollectorContract $attributes,
        protected ReflectorContract $reflection,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function getListeners(string ...$classes): array
    {
        $listeners = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            /** @var Attribute[] $attributes */
            $attributes = $this->attributes->forClassAndMembers($class, Attribute::class);

            // Get all the attributes for each class and iterate through them
            foreach ($attributes as $attribute) {
                $reflection = $attribute->getReflection();
                $method     = null;

                if ($reflection instanceof ReflectionMethod) {
                    $method     = $reflection->getName();
                }

                $listener = $this->getListenerFromAttribute($attribute);
                $listener = $this->updateDispatch($listener, $class, $method);

                $listeners[] = $this->setListenerProperties($listener);
            }
        }

        return $listeners;
    }

    /**
     * @param class-string          $class  The class name
     * @param non-empty-string|null $method The method name
     */
    protected function updateDispatch(ListenerContract $listener, string $class, string|null $method = null): ListenerContract
    {
        if ($method === null) {
            $dispatch = new ClassDispatch($class);
        } else {
            $dispatch = new MethodDispatch($class, $method);
        }

        return $listener->withDispatch($dispatch);
    }

    /**
     * Set the properties for a listener attribute.
     *
     * @throws ReflectionException
     */
    protected function setListenerProperties(ListenerContract $listener): ListenerContract
    {
        $dispatch     = $listener->getDispatch();
        $dependencies = [];

        if ($dispatch instanceof MethodDispatchContract) {
            $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());

            $dependencies = $this->reflection->getDependencies($methodReflection);
        } elseif (method_exists($dispatch->getClass(), '__construct')) {
            $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), '__construct');

            $dependencies = $this->reflection->getDependencies($methodReflection);
        }

        return $listener->withDispatch(
            $dispatch->withDependencies($dependencies)
        );
    }

    /**
     * Get a listener from an attribute.
     *
     * @param ListenerContract $attribute The attribute
     */
    protected function getListenerFromAttribute(ListenerContract $attribute): ListenerContract
    {
        return new Listener(
            eventId: $attribute->getEventId(),
            name: $attribute->getName(),
            dispatch: $attribute->getDispatch()
        );
    }
}
