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

namespace Valkyrja\Container\Commands;

use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Handlers\Handler;
use Valkyrja\Console\Support\ProvidesCommand;

use const LOCK_EX;

/**
 * Class ContainerCache.
 *
 * @author Melech Mizrachi
 */
class ContainerCache extends Handler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'container:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the container cache';

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

        $cache = container()->getCacheable();

        // Get the results of the cache attempt
        $result = file_put_contents(
            config(ConfigKey::CONTAINER_CACHE_FILE_PATH),
            '<?php

declare(strict_types=1); return ' . var_export($cache, true) . ';',
            LOCK_EX
        );

        if ($result === false) {
            output()->writeMessage('An error occurred while generating container cache.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Container cache generated successfully', true);

        return ExitCode::SUCCESS;
    }
}
