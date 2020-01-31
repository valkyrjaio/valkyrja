<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Commands;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class RoutingCache.
 *
 * @author Melech Mizrachi
 */
class RoutesCacheCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'routes:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the routes cache';
    public const DESCRIPTION       = '';

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

        $cache = router()->getCacheable();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::ROUTING_CACHE_FILE_PATH),
            '<?php

declare(strict_types=1); return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating routes cache.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Routes cache generated successfully', true);

        return ExitCode::SUCCESS;
    }
}
