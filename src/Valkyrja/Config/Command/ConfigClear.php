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

namespace Valkyrja\Config\Command;

use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Support\Provides;

use function is_file;
use function unlink;
use function Valkyrja\config;
use function Valkyrja\output;

/**
 * Class ConfigClear.
 *
 * @author Melech Mizrachi
 */
class ConfigClear extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'config:clear';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Clear the config cache';

    /**
     * @inheritDoc
     */
    public function run(): int
    {
        /** @var array{cacheFilePath: string} $configCache */
        $configCache = config();

        $cacheFilePath = $configCache['cacheFilePath'];

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        output()->writeMessage('Config cache cleared successfully', true);

        return 0;
    }
}
