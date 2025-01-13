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
 * Interface MethodDispatch.
 *
 * @author Melech Mizrachi
 */
interface MethodDispatch extends ClassDispatch
{
    /**
     * @param callable|array{0: class-string, 1: string} $callable
     *
     * @return static
     */
    public static function fromCallableOrArray(callable|array $callable): static;

    /**
     * Get the method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Create a new dispatch with the specified method.
     *
     * @param string $method The method
     *
     * @return static
     */
    public function withMethod(string $method): static;

    /**
     * Determine whether this is a static method.
     *
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * Create a new dispatch with whether this is a static method.
     *
     * @param bool $isStatic The static flag
     *
     * @return $this
     */
    public function withIsStatic(bool $isStatic): static;
}
