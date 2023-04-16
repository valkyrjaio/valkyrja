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

namespace Valkyrja\Test\Assert;

use Throwable;

/**
 * Interface Exceptions.
 *
 * @author Melech Mizrachi
 */
interface Exceptions extends Asserter
{
    /**
     * Get the error message for when an exception is expected but not thrown.
     */
    public static function getExpectedErrorMessage(): string;

    /**
     * Get the error message for when an exception is not expected but is thrown.
     */
    public static function getUnexpectedErrorMessage(string $actualClassName, string $actualMessage): string;

    /**
     * Get the error message for when the expected class name does not match what was thrown.
     */
    public static function getIncorrectClassNameErrorMessage(string $expected, string $actual): string;

    /**
     * Get the error message for when the expected message does not match what was thrown.
     */
    public static function getIncorrectMessageErrorMessage(string $expected, string $actual): string;

    /**
     * Set an expected class name to be thrown.
     *
     * @param class-string $className
     */
    public function className(string $className): void;

    /**
     * Set an expected message to be thrown.
     */
    public function message(string $message): void;

    /**
     * Set that an exception is expected to be thrown.
     */
    public function expecting(): void;

    /**
     * Verify the exception thrown (or not thrown) against what is expected.
     */
    public function verify(Throwable $exception = null): void;
}
