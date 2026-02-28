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

interface ConstantDispatchContract extends DispatchContract
{
    /**
     * Get the constant.
     *
     * @return non-empty-string
     */
    public function getConstant(): string;

    /**
     * Create a new dispatch with the specified constant.
     *
     * @param non-empty-string $constant The constant
     */
    public function withConstant(string $constant): static;

    /**
     * Determine if there is a class name.
     */
    public function hasClass(): bool;

    /**
     * Get the class name.
     *
     * @return class-string
     */
    public function getClass(): string;

    /**
     * Create a new dispatch with the specified class name.
     *
     * @param class-string $class [optional] The class name
     */
    public function withClass(string $class): static;

    /**
     * Create a new dispatch without a class name.
     */
    public function withoutClass(): static;
}
