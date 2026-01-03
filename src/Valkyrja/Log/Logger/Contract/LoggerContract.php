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

namespace Valkyrja\Log\Logger\Contract;

use Override;
use Psr\Log\LoggerInterface;
use Stringable;
use Throwable;
use Valkyrja\Log\Enum\LogLevel;

interface LoggerContract extends LoggerInterface
{
    /**
     * Log a debug message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function debug(string|Stringable $message, array $context = []): void;

    /**
     * Log an info message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function info(string|Stringable $message, array $context = []): void;

    /**
     * Log a notice message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function notice(string|Stringable $message, array $context = []): void;

    /**
     * Log a warning message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function warning(string|Stringable $message, array $context = []): void;

    /**
     * Log a error message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function error(string|Stringable $message, array $context = []): void;

    /**
     * Log a critical message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function critical(string|Stringable $message, array $context = []): void;

    /**
     * Log a alert message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function alert(string|Stringable $message, array $context = []): void;

    /**
     * Log a emergency message.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    #[Override]
    public function emergency(string|Stringable $message, array $context = []): void;

    /**
     * Log a message.
     *
     * @param LogLevel                $level   The log level
     * @param array<array-key, mixed> $context [optional] The context
     *
     * @psalm-suppress MoreSpecificImplementedParamType This is fine, we want the type hinting here
     */
    #[Override]
    public function log($level, string|Stringable $message, array $context = []): void;

    /**
     * Log a throwable.
     *
     * @param array<array-key, mixed> $context [optional] The context
     */
    public function throwable(Throwable $throwable, string|Stringable $message, array $context = []): void;
}
