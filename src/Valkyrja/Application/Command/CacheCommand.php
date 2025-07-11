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

namespace Valkyrja\Application\Command;

use Valkyrja\Application\Config;
use Valkyrja\Application\Data;
use Valkyrja\Application\Env;
use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CliCollectionContract;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Data as ContainerData;
use Valkyrja\Event\Collection\Contract\Collection as EventCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection as HttpCollectionContract;

use const LOCK_EX;

/**
 * Class CacheCommand.
 *
 * @author Melech Mizrachi
 */
class CacheCommand
{
    public const string NAME = 'config:cache';

    #[CommandAttribute(
        name: self::NAME,
        description: 'Cache the config',
        helpText: new Message('A command to cache the config.'),
    )]
    public function run(
        Container $container,
        CliCollectionContract $cliCollection,
        EventCollection $eventCollection,
        HttpCollectionContract $routerCollection,
        Env $env,
        OutputFactory $outputFactory
    ): Output {
        $env = clone $env;

        /** @var non-empty-string $cacheFilePath */
        $cacheFilePath = $env::APP_CACHE_FILE_PATH;

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            unlink($cacheFilePath);
        }

        $containerData = $container->getData();

        $singletons = $containerData->singletons;
        unset($singletons[Config::class]);

        $data = new Data(
            container: new ContainerData(
                aliases: $containerData->aliases,
                contextServices: $containerData->contextServices,
                deferred: $containerData->deferred,
                deferredCallback: $containerData->deferredCallback,
                services: $containerData->services,
                singletons: $singletons,
                providers: $containerData->providers,
            ),
            event: $eventCollection->getData(),
            cli: $cliCollection->getData(),
            http: $routerCollection->getData(),
        );

        // Get the results of the cache attempt
        $result = file_put_contents($cacheFilePath, serialize($data), LOCK_EX);

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
