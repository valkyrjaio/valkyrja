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
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;

class HttpRoutingCliServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ListCommand::class => [self::class, 'publishListCommand'],
        ];
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
