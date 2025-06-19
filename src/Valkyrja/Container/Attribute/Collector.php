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

namespace Valkyrja\Container\Attribute;

use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Attribute\Contract\Collector as Contract;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 */
class Collector implements Contract
{
    public function __construct(
        protected Attributes $attributes
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string ...$classes The classes
     *
     * @return Service[]
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAttributesByType(Service::class, ...$classes);
    }

    /**
     * @inheritDoc
     *
     * @param class-string ...$classes The classes
     *
     * @return Alias[]
     */
    public function getAliases(string ...$classes): array
    {
        return $this->getAttributesByType(Alias::class, ...$classes);
    }

    /**
     * @inheritDoc
     *
     * @param class-string ...$classes The classes
     *
     * @return Context[]
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAttributesByType(Context::class, ...$classes);
    }

    /**
     * @template Attribute of Service|Alias|Context
     *
     * @param class-string<Attribute> $attributeClass The attribute class name
     * @param class-string            ...$classes     The classes
     *
     * @return Attribute[]
     */
    protected function getAttributesByType(string $attributeClass, string ...$classes): array
    {
        $attributes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            /** @var Attribute[] $attributes */
            $attributes = [
                ...$attributes,
                ...$this->attributes->forClassAndMembers($class, $attributeClass),
            ];
        }

        return $attributes;
    }
}
