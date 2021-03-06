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

namespace Valkyrja\Console\Commands;

use Valkyrja\Config\Constants\ConfigKey;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\Provides;

use function file_put_contents;
use function Valkyrja\app;
use function Valkyrja\config;
use function Valkyrja\console;
use function Valkyrja\output;
use function var_export;

use const LOCK_EX;

/**
 * Class ConsoleCache.
 *
 * @author Melech Mizrachi
 */
class ConsoleCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'console:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the console cache';

    /**
     * Run the command.
     *
     * @return int
     */
    public function run(): int
    {
        app()->setup(
            [
                'app' => [
                    'debug' => false,
                    'env'   => 'production',
                ],
            ],
            true
        );

        $cache = console()->getCacheable();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::CONSOLE_CACHE_FILE_PATH),
            '<?php

declare(strict_types=1); return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating console cache.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Console cache generated successfully', true);

        return ExitCode::SUCCESS;
    }
}
