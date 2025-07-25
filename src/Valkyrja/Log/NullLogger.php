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

namespace Valkyrja\Log;

use Override;
use Throwable;
use Valkyrja\Log\Contract\Logger as Contract;
use Valkyrja\Log\Enum\LogLevel;

/**
 * Class NullLogger.
 *
 * @author Melech Mizrachi
 */
class NullLogger implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function debug(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function info(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function notice(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function warning(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function error(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function critical(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function alert(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function emergency(string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function log(LogLevel $level, string $message, array $context = []): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exception(Throwable $exception, string $message, array $context = []): void
    {
    }
}
