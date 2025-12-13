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

use Override;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch as Contract;

/**
 * Class ClassDispatch.
 *
 * @author Melech Mizrachi
 */
class ClassDispatch extends Dispatch implements Contract
{
    /**
     * @param class-string                               $class        The class name
     * @param array<array-key, mixed>|null               $arguments    The arguments
     * @param array<non-empty-string, class-string>|null $dependencies The dependencies
     */
    public function __construct(
        protected string $class,
        protected array|null $arguments = null,
        protected array|null $dependencies = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withClass(string $class): static
    {
        $new = clone $this;

        $new->class = $class;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArguments(): array|null
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withArguments(array|null $arguments = null): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDependencies(): array|null
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDependencies(array|null $dependencies = null): static
    {
        $new = clone $this;

        $new->dependencies = $dependencies;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __toString(): string
    {
        return $this->class;
    }
}
