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

interface CallableDispatchContract extends DispatchContract
{
    /**
     * Get the callable.
     */
    public function getCallable(): callable;

    /**
     * Create a new dispatch with the specified callable.
     *
     * @param callable $callable The callable
     */
    public function withCallable(callable $callable): static;

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
