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

namespace Valkyrja\Dispatcher\Data\Contract;

/**
 * Interface ClassDispatch.
 *
 * @author Melech Mizrachi
 */
interface ClassDispatch extends Dispatch
{
    /**
     * @return class-string
     */
    public function getClass(): string;

    /**
     * Create a new dispatch with the specified class name.
     *
     * @param class-string $class
     *
     * @return static
     */
    public function withClass(string $class): static;

    /**
     * Get the arguments.
     *
     * @return array<array-key, mixed>|null
     */
    public function getArguments(): ?array;

    /**
     * Create a new dispatch with the specified arguments.
     *
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return static
     */
    public function withArguments(?array $arguments = null): static;

    /**
     * Get the dependencies.
     *
     * @return string[]|null
     */
    public function getDependencies(): ?array;

    /**
     * Create a new dispatch with the specified dependencies.
     *
     * @param string[]|null $dependencies The dependencies
     *
     * @return static
     */
    public function withDependencies(?array $dependencies = null): static;
}
