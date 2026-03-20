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
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Abstract\Provider;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;

final class CliServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            GenerateDataCommand::class => [self::class, 'publishGenerateDataCommand'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            GenerateDataCommand::class,
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
                $container->getSingleton(HttpConfig::class),
                $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }
}
