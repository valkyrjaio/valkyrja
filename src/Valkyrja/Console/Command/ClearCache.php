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

namespace Valkyrja\Console\Command;

use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Support\Provides;

use function is_file;
use function unlink;
use function Valkyrja\config;
use function Valkyrja\output;

/**
 * Class Clear.
 *
 * @author Melech Mizrachi
 */
class ClearCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'clear:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Clear the optimized application';

    /**
     * @inheritDoc
     */
    public function run(): int
    {
        $cacheFilePath = config()['cacheFilePath'];

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        output()->writeMessage('Application cache cleared successfully', true);

        return ExitCode::SUCCESS;
    }
}
