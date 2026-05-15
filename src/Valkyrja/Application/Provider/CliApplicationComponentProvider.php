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

namespace Valkyrja\Application\Provider;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Cli\Interaction\Provider\CliInteractionComponentProvider;
use Valkyrja\Cli\Middleware\Provider\CliMiddlewareComponentProvider;
use Valkyrja\Cli\Routing\Provider\CliRoutingComponentProvider;
use Valkyrja\Cli\Server\Provider\CliServerComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;

class CliApplicationComponentProvider implements ComponentProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getComponentProviders(ApplicationContract $app): array
    {
        return [
            new ApplicationComponentProvider(),
            new CliInteractionComponentProvider(),
            new CliMiddlewareComponentProvider(),
            new CliRoutingComponentProvider(),
            new CliServerComponentProvider(),
            new LogComponentProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpProviders(ApplicationContract $app): array
    {
        return [];
    }
}
