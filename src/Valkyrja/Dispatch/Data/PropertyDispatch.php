<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data;

use Override;
use Valkyrja\Dispatch\Data\Contract\PropertyDispatchContract;

class PropertyDispatch extends ClassDispatch implements PropertyDispatchContract
{
    /**
     * @param class-string                          $class        The class name
     * @param non-empty-string                      $property     The property name
     * @param array<non-empty-string, mixed>        $arguments    The arguments
     * @param array<non-empty-string, class-string> $dependencies The dependencies
     */
    public function __construct(
        string $class,
        protected string $property,
        protected bool $isStatic = false,
        array $arguments = [],
        array $dependencies = []
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
    #[Override]
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withProperty(string $property): static
    {
        $new = clone $this;

        $new->property = $property;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withIsStatic(bool $isStatic): static
    {
        $new = clone $this;

        $new->isStatic = $isStatic;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __toString(): string
    {
        return $this->class
            . ($this->isStatic ? '::' : '->')
            . $this->property;
    }
}
