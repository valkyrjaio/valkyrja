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

namespace Valkyrja\Container\Collector;

use Override;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Service;
use Valkyrja\Container\Collector\Contract\CollectorContract as Contract;

/**
 * Class AttributeCollector.
 */
class AttributeCollector implements Contract
{
    /**
     * AttributeCollector constructor.
     */
    public function __construct(
        protected AttributeCollectorContract $attributes = new Collector()
    ) {
    }

    /**
     * @inheritDoc
     *
     * @param class-string ...$classes The classes
     *
     * @return Service[]
     */
    #[Override]
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
    #[Override]
    public function getAliases(string ...$classes): array
    {
        return $this->getAttributesByType(Alias::class, ...$classes);
    }

    /**
     * @template T of Service|Alias
     *
     * @param class-string<T> $attributeClass The attribute class name
     * @param class-string    ...$classes     The classes
     *
     * @return T[]
     */
    protected function getAttributesByType(string $attributeClass, string ...$classes): array
    {
        $attributes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            /** @var T[] $attributes */
            $attributes = [
                ...$attributes,
                ...$this->attributes->forClassAndMembers($class, $attributeClass),
            ];
        }

        return $attributes;
    }
}
