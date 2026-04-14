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
use Valkyrja\Container\Provider\ContainerComponentProvider;
use Valkyrja\Dispatch\Provider\DispatchComponentProvider;
use Valkyrja\Event\Provider\EventComponentProvider;
use Valkyrja\Http\Message\Provider\HttpMessageComponentProvider;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliComponentProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingComponentProvider;
use Valkyrja\Http\Server\Provider\HttpServerComponentProvider;
use Valkyrja\Log\Provider\LogComponentProvider;
use Valkyrja\View\Provider\ViewComponentProvider;

class HttpApplicationComponentProvider implements ComponentProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getComponentProviders(ApplicationContract $app): array
    {
        return [
            ContainerComponentProvider::class,
            DispatchComponentProvider::class,
            EventComponentProvider::class,
            HttpMessageComponentProvider::class,
            HttpMiddlewareComponentProvider::class,
            HttpRoutingComponentProvider::class,
            HttpRoutingCliComponentProvider::class,
            HttpServerComponentProvider::class,
            LogComponentProvider::class,
            ViewComponentProvider::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getHttpProviders(ApplicationContract $app): array
    {
        return [];
    }
}
