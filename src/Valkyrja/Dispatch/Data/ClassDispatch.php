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
     * @param class-string                               $class        The class name
     * @param array<non-empty-string, mixed>|null        $arguments    The arguments
     * @param array<non-empty-string, class-string>|null $dependencies The dependencies
     */
    public function __construct(
        protected string $class,
        protected array|null $arguments = null,
        protected array|null $dependencies = null,
    ) {
    }

    /**
     * @param array{
     *     class: class-string,
     *     arguments: array<non-empty-string, mixed>|null,
     *     dependencies: array<non-empty-string, class-string>|null,
     * } $array The array
     */
    public static function __set_state(array $array): static
    {
        return new static(...$array);
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
