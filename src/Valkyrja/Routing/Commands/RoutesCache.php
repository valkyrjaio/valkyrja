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

namespace Valkyrja\Routing\Commands;

use Valkyrja\Config\Constants\ConfigKey;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\Provides;

use function file_put_contents;
use function Valkyrja\app;
use function Valkyrja\config;
use function Valkyrja\output;
use function Valkyrja\router;
use function var_export;

use const LOCK_EX;

/**
 * Class RoutingCache.
 *
 * @author Melech Mizrachi
 */
class RoutesCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'routes:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the routes cache';
    public const DESCRIPTION       = '';

    /**
     * @inheritDoc
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

        $cache = router()->getCollection()->getCacheable();

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
