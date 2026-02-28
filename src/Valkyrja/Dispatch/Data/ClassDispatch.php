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
use Valkyrja\Dispatch\Data\Abstract\Dispatch;
use Valkyrja\Dispatch\Data\Contract\ClassDispatchContract;

class ClassDispatch extends Dispatch implements ClassDispatchContract
{
    /**
     * @param class-string                          $class        The class name
     * @param array<non-empty-string, mixed>        $arguments    The arguments
     * @param array<non-empty-string, class-string> $dependencies The dependencies
     */
    public function __construct(
        protected string $class,
        protected array $arguments = [],
        protected array $dependencies = [],
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
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withArguments(array $arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDependencies(array $dependencies): static
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
