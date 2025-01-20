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

use Valkyrja\Dispatcher\Data\Contract\ClassDispatch as Contract;

/**
 * Class ClassDispatch.
 *
 * @author Melech Mizrachi
 */
class ClassDispatch extends Dispatch implements Contract
{
    /**
     * @param class-string                 $class        The class name
     * @param array<array-key, mixed>|null $arguments    The arguments
     * @param string[]|null                $dependencies The dependencies
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
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @inheritDoc
     */
    public function withClass(string $class): static
    {
        $new = clone $this;

        $new->class = $class;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array|null
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function withArguments(array|null $arguments = null): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array|null
    {
        return $this->dependencies;
    }

    /**
     * @inheritDoc
     */
    public function withDependencies(array|null $dependencies = null): static
    {
        $new = clone $this;

        $new->dependencies = $dependencies;

        return $new;
    }
}
