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

namespace Valkyrja\Dispatch\Data\Contract;

/**
 * Interface ClassDispatchContract.
 *
 * @author Melech Mizrachi
 */
interface ClassDispatchContract extends DispatchContract
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
     * @return array<non-empty-string, mixed>|null
     */
    public function getArguments(): array|null;

    /**
     * Create a new dispatch with the specified arguments.
     *
     * @param array<non-empty-string, mixed>|null $arguments The arguments
     *
     * @return static
     */
    public function withArguments(array|null $arguments = null): static;

    /**
     * Get the dependencies.
     *
     * @return array<non-empty-string, class-string>|null
     */
    public function getDependencies(): array|null;

    /**
     * Create a new dispatch with the specified dependencies.
     *
     * @param array<non-empty-string, class-string>|null $dependencies The dependencies
     *
     * @return static
     */
    public function withDependencies(array|null $dependencies = null): static;
}
