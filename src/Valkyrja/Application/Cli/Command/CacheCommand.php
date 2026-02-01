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

namespace Valkyrja\Application\Cli\Command;

use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract as CliCollectionContract;
use Valkyrja\Event\Collection\Contract\CollectionContract as EventCollection;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract as HttpCollectionContract;
use Valkyrja\Support\Directory\Directory;

use const LOCK_EX;

class CacheCommand
{
    public const string NAME = 'config:cache';

    #[Route(
        name: self::NAME,
        description: 'Cache the config',
        helpText: new Message('A command to cache the config.'),
    )]
    public function run(
        CliCollectionContract $cliCollection,
        EventCollection $eventCollection,
        HttpCollectionContract $routerCollection,
        Env $env,
        OutputFactoryContract $outputFactory
    ): OutputContract {
        /** @var non-empty-string $cacheFilepath */
        $cacheFilepath = $env::APP_CACHE_FILE_PATH;
        $cacheFilename = Directory::basePath($cacheFilepath);

        // If the cache file already exists, delete it
        if (is_file($cacheFilename)) {
            @unlink($cacheFilename);
        }

        $data = new Data(
            event: $eventCollection->getData(),
            cli: $cliCollection->getData(),
            http: $routerCollection->getData(),
        );

        // Get the results of the cache attempt
        $result = @file_put_contents($cacheFilename, serialize($data), LOCK_EX);

        if ($result === false) {
            return $outputFactory
                ->createOutput(exitCode: ExitCode::ERROR)
                ->withMessages(
                    new Banner(new ErrorMessage('An error occurred while caching the config.')),
                    new NewLine(),
                );
        }

        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Banner(new SuccessMessage('Application config cached successfully.')),
                new NewLine(),
            );
    }
}
