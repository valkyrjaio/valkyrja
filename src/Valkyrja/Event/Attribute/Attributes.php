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

namespace Valkyrja\Event\Attribute;

use ReflectionException;
use Valkyrja\Attribute\Contract\Attributes as AttributeAttributes;
use Valkyrja\Event\Attribute\Contract\Attributes as Contract;
use Valkyrja\Event\Attribute\Listener as Attribute;
use Valkyrja\Event\Data\Contract\Listener;
use Valkyrja\Event\Data\Listener as Model;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class Attributes.
 *
 * @author Melech Mizrachi
 */
class Attributes implements Contract
{
    public function __construct(
        protected AttributeAttributes $attributes,
        protected Reflection $reflection,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
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
        $dispatch         = $attribute->getDispatch();
        $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());

        $dependencies = $this->reflection->getDependencies($methodReflection);

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
