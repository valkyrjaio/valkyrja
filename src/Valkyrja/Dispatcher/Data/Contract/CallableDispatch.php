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
 * Interface CallableDispatch.
 *
 * @author Melech Mizrachi
 */
interface CallableDispatch extends Dispatch
{
    /**
     * Get the callable.
     *
     * @return callable
     */
    public function getCallable(): callable;

    /**
     * Create a new dispatch with the specified callable.
     *
     * @param callable $callable The callable
     *
     * @return static
     */
    public function withCallable(callable $callable): static;

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
