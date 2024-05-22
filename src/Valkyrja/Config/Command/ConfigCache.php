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

use JsonException;
use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Support\Provides;

use function file_put_contents;
use function is_file;
use function json_decode;
use function json_encode;
use function unlink;
use function Valkyrja\config;
use function Valkyrja\output;
use function var_export;

use const JSON_THROW_ON_ERROR;
use const LOCK_EX;
use const PHP_EOL;

/**
 * Class ConfigCache.
 *
 * @author Melech Mizrachi
 */
class ConfigCache extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'config:cache';
    public const PATH              = self::COMMAND;
    public const SHORT_DESCRIPTION = 'Generate the config cache';

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function run(): int
    {
        $cacheFilePath = config()['cacheFilePath'];

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $config                 = config();
        $config['app']['debug'] = false;
        $config['app']['env']   = 'production';

        $asArray  = json_decode(json_encode($config, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        $asString = '<?php return ' . var_export($asArray, true) . ';' . PHP_EOL;
        // $serialized = serialize($config);
        // $serialized = preg_replace('/O:\d+:"[^"]++"/', 'O:8:"stdClass"', $serialized);

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, $asString, LOCK_EX);

        if ($result === false) {
            output()->writeMessage('An error occurred while generating config cache.', true);

            return 1;
        }

        output()->writeMessage('Config cache generated successfully', true);

        return 0;
    }
}
