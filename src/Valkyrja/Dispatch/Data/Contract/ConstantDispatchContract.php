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
 * Interface ConstantDispatchContract.
 */
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
     *
     * @return static
     */
    public function withConstant(string $constant): static;

    /**
     * Get the class name.
     *
     * @return class-string|null
     */
    public function getClass(): string|null;

    /**
     * Create a new dispatch with the specified class name.
     *
     * @param class-string|null $class [optional] The class name
     *
     * @return static
     */
    public function withClass(string|null $class = null): static;
}
