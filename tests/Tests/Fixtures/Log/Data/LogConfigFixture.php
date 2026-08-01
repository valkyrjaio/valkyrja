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

namespace Valkyrja\Tests\Fixtures\Log\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Log\Data\Contract\LogConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;

final class LogConfigFixture extends Config implements LogConfigContract
{
    /**
     * @param class-string<LoggerContract> $defaultLogger
     */
    public function __construct(
        public string $defaultLogger = NullLogger::class,
    ) {
        parent::__construct();
    }
}
