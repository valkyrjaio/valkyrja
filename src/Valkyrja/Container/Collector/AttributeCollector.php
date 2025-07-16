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
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Service;
use Valkyrja\Container\Attribute\Service as ServiceAttribute;
use Valkyrja\Container\Collector\Contract\Collector as Contract;

/**
 * Class AttributeCollector.
 *
 * @author Melech Mizrachi
 */
class AttributeCollector implements Contract
{
    /**
     * AttributeCollector constructor.
     */
    public function __construct(
        protected Attributes $attributes = new \Valkyrja\Attribute\Attributes()
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
        return $this->getAttributesByType(ServiceAttribute::class, ...$classes);
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
     * @template T of ServiceAttribute|Alias
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
