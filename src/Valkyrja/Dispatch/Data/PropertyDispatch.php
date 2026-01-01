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

namespace Valkyrja\Dispatch\Data;

use Override;
use Valkyrja\Dispatch\Data\Contract\PropertyDispatchContract as Contract;

class PropertyDispatch extends ClassDispatch implements Contract
{
    /**
     * @param class-string                               $class        The class name
     * @param non-empty-string                           $property     The property name
     * @param array<non-empty-string, mixed>|null        $arguments    The arguments
     * @param array<non-empty-string, class-string>|null $dependencies The dependencies
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
