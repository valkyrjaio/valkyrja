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

namespace Valkyrja\Dispatcher\Data;

use Valkyrja\Dispatcher\Data\Contract\PropertyDispatch as Contract;

/**
 * Class PropertyDispatch.
 *
 * @author Melech Mizrachi
 */
class PropertyDispatch extends ClassDispatch implements Contract
{
    /**
     * @param class-string                 $class        The class name
     * @param array<array-key, mixed>|null $arguments    The arguments
     * @param string[]|null                $dependencies The dependencies
     */
    public function __construct(
        string $class,
        protected string $property,
        protected bool $isStatic = false,
        array|null $arguments = null,
        array|null $dependencies = null
    ) {
        parent::__construct(
            class: $class,
            arguments: $arguments,
            dependencies: $dependencies
        );
    }

    /**
     * @inheritDoc
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @inheritDoc
     */
    public function withProperty(string $property): static
    {
        $new = clone $this;

        $new->property = $property;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @inheritDoc
     */
    public function withIsStatic(bool $isStatic): static
    {
        $new = clone $this;

        $new->isStatic = $isStatic;

        return $new;
    }
}
