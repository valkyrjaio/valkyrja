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

namespace Valkyrja\Routing\Facades;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router as Contract;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 *
 * @method static array getConfig()
 * @method static Collection getCollection()
 * @method static Matcher getMatcher()
 * @method static void addRoute(Route $route)
 * @method static Route[] getRoutes()
 * @method static Route getRoute(string $name)
 * @method static bool hasRoute(string $name)
 * @method static string getUrl(string $name, array $data = null, bool $absolute = null)
 * @method static Route getRouteFromRequest(Request $request)
 * @method static Route|null getRouteByPath(string $path, string $method = null)
 * @method static bool isInternalUri(string $uri)
 * @method static Response dispatch(Request $request)
 */
class Router extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
