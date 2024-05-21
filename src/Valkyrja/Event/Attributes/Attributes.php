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

namespace Valkyrja\Event\Attributes;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Attribute\Contract\Attributes as AttributeAttributes;
use Valkyrja\Event\Attributes as Contract;
use Valkyrja\Event\Attributes\Listener as Attribute;
use Valkyrja\Event\Listener;
use Valkyrja\Event\Models\Listener as Model;
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
            /** @var Attribute $attribute */
            foreach ($attributes as $key => $attribute) {
                $this->setListenerProperties($attribute);

                // Check if the attribute has a method. If not there's no where for this event to be dispatched to
                if ($attribute->getMethod() === null) {
                    // So unset it as it's an invalid attribute
                    unset($attributes[$key]);

                    // Continue onward
                    continue;
                }
                // Set the attribute in the attributes list
                $listeners[] = $this->getListenerFromAttribute($attribute);
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
     * @return void
     */
    protected function setListenerProperties(Attribute $attribute): void
    {
        if (! ($class = $attribute->getClass())) {
            throw new InvalidArgumentException('Invalid class defined in listener attribute.');
        }

        $classReflection = $this->reflection->forClass($class);

        if (($method = $attribute->getMethod()) !== null || $classReflection->hasMethod('__construct')) {
            $method ??= '__construct';

            $attribute->setMethod($method);
            /** @var non-empty-string $method */
            $methodReflection = $this->reflection->forClassMethod($class, $method);

            // Set the dependencies
            $attribute->setDependencies($this->reflection->getDependencies($methodReflection));
        }

        $attribute->setMatches();
    }

    /**
     * Get a listener from an attribute.
     *
     * @param Attribute $attribute The attribute
     *
     * @return Listener
     */
    protected function getListenerFromAttribute(Attribute $attribute): Listener
    {
        return Model::fromArray($attribute->asArray());
    }
}
