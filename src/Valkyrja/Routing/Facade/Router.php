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

namespace Valkyrja\Routing\Facade;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Routing\Collection\Contract\Collection;
use Valkyrja\Routing\Contract\Router as Contract;
use Valkyrja\Routing\Matcher\Contract\Matcher;
use Valkyrja\Routing\Model\Contract\Route;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 *
 * @method static array      getConfig()
 * @method static Collection getCollection()
 * @method static Matcher    getMatcher()
 * @method static void       addRoute(Route $route)
 * @method static Route[]    getRoutes()
 * @method static Route      getRoute(string $name)
 * @method static bool       hasRoute(string $name)
 * @method static string     getUrl(string $name, array $data = null, bool $absolute = null)
 * @method static Route      getRouteFromRequest(ServerRequest $request)
 * @method static Route|null getRouteByPath(string $path, string $method = null)
 * @method static bool       isInternalUri(string $uri)
 * @method static Response   dispatch(ServerRequest $request)
 */
class Router extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
