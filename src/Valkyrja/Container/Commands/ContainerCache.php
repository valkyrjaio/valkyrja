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

namespace Valkyrja\Container\Commands;

use JsonException;
use Valkyrja\Console\Commanders\Commander;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Container\Managers\CacheableContainer;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function file_put_contents;
use function Valkyrja\config;
use function Valkyrja\container;
use function Valkyrja\output;
use function var_export;

use const JSON_THROW_ON_ERROR;
use const LOCK_EX;
use const PHP_EOL;

/**
 * Class ContainerCache.
 *
 * @author Melech Mizrachi
 */
class ContainerCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'container:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the container cache';

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function run(): int
    {
        $configCache   = config();
        $cacheFilePath = $configCache['container']['cacheFilePath'];

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $configCache['app']['debug'] = false;
        $configCache['app']['env']   = 'production';

        /** @var CacheableContainer $container */
        $container = container();

        $cache = $container->getCacheable();

        $asArray  = json_decode(json_encode($cache, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        $asString = '<?php return ' . var_export(Arr::withoutNull($asArray), true) . ';' . PHP_EOL;

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $asString, LOCK_EX);

        if ($result === false) {
            output()->writeMessage('An error occurred while generating container cache.', true);

            return ExitCode::FAILURE;
        }

        output()->writeMessage('Container cache generated successfully', true);

        return ExitCode::SUCCESS;
    }
}
