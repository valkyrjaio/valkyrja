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
     */
    public function withClass(string $class): static;

    /**
     * Get the arguments.
     *
     * @return array<non-empty-string, mixed>
     */
    public function getArguments(): array;

    /**
     * Create a new dispatch with the specified arguments.
     *
     * @param array<non-empty-string, mixed> $arguments The arguments
     */
    public function withArguments(array $arguments): static;

    /**
     * Get the dependencies.
     *
     * @return array<non-empty-string, class-string>
     */
    public function getDependencies(): array;

    /**
     * Create a new dispatch with the specified dependencies.
     *
     * @param array<non-empty-string, class-string> $dependencies The dependencies
     */
    public function withDependencies(array $dependencies): static;
}
