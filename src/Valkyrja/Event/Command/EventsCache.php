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

namespace Valkyrja\Event\Command;

use JsonException;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Support\Provides;
use Valkyrja\Event\Collection\CacheableCollection as CacheableEvents;
use Valkyrja\Event\Collection\Contract\Collection;
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
 * Class EventsCache.
 *
 * @author Melech Mizrachi
 */
class EventsCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'events:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the events cache';

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function run(): int
    {
        /** @var array{app: array{debug: bool, env: string}, event: array{cacheFilePath: string}} $configCache */
        $configCache   = config();
        $cacheFilePath = $configCache['event']['cacheFilePath'];

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $configCache['app']['debug'] = false;
        $configCache['app']['env']   = 'production';

        /** @var CacheableEvents $events */
        $events = container()->getSingleton(Collection::class);

        $cache = $events->getCacheable();

        /** @var array<string, mixed> $asArray */
        $asArray  = json_decode(json_encode($cache, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        $asString = '<?php return ' . var_export(Arr::newWithoutNull($asArray), true) . ';' . PHP_EOL;

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $asString, LOCK_EX);

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
