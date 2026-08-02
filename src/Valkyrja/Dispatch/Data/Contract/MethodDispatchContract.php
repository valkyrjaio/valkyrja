<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data\Contract;

interface MethodDispatchContract extends ClassDispatchContract
{
    /**
     * @param callable|array{0: class-string, 1: non-empty-string} $callable
     */
    public static function fromCallableOrArray(callable|array $callable): static;

    /**
     * Get the method.
     *
     * @return non-empty-string
     */
    public function getMethod(): string;

    /**
     * Create a new dispatch with the specified method.
     *
     * @param non-empty-string $method The method
     */
    public function withMethod(string $method): static;

    /**
     * Determine whether this is a static method.
     */
    public function isStatic(): bool;

    /**
     * Create a new dispatch with whether this is a static method.
     *
     * @param bool $isStatic The static flag
     */
    public function withIsStatic(bool $isStatic): static;
}
