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
use Valkyrja\Attribute\Collector\Contract\Collector;
use Valkyrja\Dispatch\Data\Contract\MethodDispatch;
use Valkyrja\Event\Attribute\Listener as Attribute;
use Valkyrja\Event\Collector\Contract\Collector as Contract;
use Valkyrja\Event\Data\Contract\Listener;
use Valkyrja\Event\Data\Listener as Model;
use Valkyrja\Reflection\Reflector\Contract\Reflector;

/**
 * Class AttributeCollector.
 *
 * @author Melech Mizrachi
 */
class AttributeCollector implements Contract
{
    public function __construct(
        protected Collector $attributes,
        protected Reflector $reflection,
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
                $listeners[] = $this->setListenerProperties($attribute);
            }
        }

        return $listeners;
    }

    /**
     * Set the properties for a listener attribute.
     *
     * @param Attribute $attribute
     *
     * @throws ReflectionException
     *
     * @return Listener
     */
    protected function setListenerProperties(Attribute $attribute): Listener
    {
        $dispatch     = $attribute->getDispatch();
        $dependencies = [];

        if ($dispatch instanceof MethodDispatch) {
            $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());

            $dependencies = $this->reflection->getDependencies($methodReflection);
        } elseif (method_exists($dispatch->getClass(), '__construct')) {
            $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), '__construct');

            $dependencies = $this->reflection->getDependencies($methodReflection);
        }

        return $this->getListenerFromAttribute(
            $attribute->withDispatch(
                $dispatch->withDependencies($dependencies)
            )
        );
    }

    /**
     * Get a listener from an attribute.
     *
     * @param Listener $attribute The attribute
     *
     * @return Listener
     */
    protected function getListenerFromAttribute(Listener $attribute): Listener
    {
        return new Model(
            eventId: $attribute->getEventId(),
            name: $attribute->getName(),
            dispatch: $attribute->getDispatch()
        );
    }
}
