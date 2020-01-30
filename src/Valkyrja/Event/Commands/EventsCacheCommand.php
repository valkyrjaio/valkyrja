<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Event\Commands;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class EventsCache.
 *
 * @author Melech Mizrachi
 */
class EventsCacheCommand extends CommandHandler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'events:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the events cache';

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

        $cache = events()->getCacheable();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::EVENTS_CACHE_FILE_PATH),
            '<?php

declare(strict_types=1); return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage(
                'An error occurred while generating events cache.',
                true
            );

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Events cache generated successfully', true);

        return ExitCode::SUCCESS;
    }
}
