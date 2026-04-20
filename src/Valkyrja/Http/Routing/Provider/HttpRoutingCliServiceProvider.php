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

namespace Valkyrja\Http\Routing\Provider;

use Override;
use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;

class HttpRoutingCliServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            GenerateDataCommand::class => [self::class, 'publishGenerateDataCommand'],
            ListCommand::class         => [self::class, 'publishListCommand'],
        ];
    }

    /**
     * Publish the generate data command service.
     */
    public static function publishGenerateDataCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            GenerateDataCommand::class,
            new GenerateDataCommand(
                $container->getSingleton(Env::class),
                $container->getSingleton(HttpConfigContract::class),
                $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the list command service.
     */
    public static function publishListCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            ListCommand::class,
            new ListCommand()
        );
    }
}
