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

use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\ContextAlias;
use Valkyrja\Container\Attribute\ContextService;
use Valkyrja\Container\Attribute\Service as Attribute;
use Valkyrja\Container\Collector\Contract\Collector as Contract;

/**
 * Class AttributeCollector.
 *
 * @author Melech Mizrachi
 */
class AttributeCollector implements Contract
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
     * @return Attribute
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAttributesByType(Attribute::class, ...$classes);
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
     * @return ContextService[]
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAttributesByType(ContextService::class, ...$classes);
    }

    /**
     * @inheritDoc
     *
     * @param class-string ...$classes The classes
     *
     * @return ContextAlias[]
     */
    public function getContextAliases(string ...$classes): array
    {
        return $this->getAttributesByType(ContextAlias::class, ...$classes);
    }

    /**
     * @template Attribute of Attribute|Alias|ContextService|ContextAlias
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
