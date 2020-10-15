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

namespace Valkyrja\Routing\Support;

use Valkyrja\Container\Container;
use Valkyrja\Event\Events;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;

/**
 * Abstract Class Controller.
 *
 * @author Melech Mizrachi
 */
abstract class Controller
{
    /**
     * The container.
     *
     * @var Container
     */
    public static Container $container;

    /**
     * The events.
     *
     * @var Events
     */
    public static Events $events;

    /**
     * The request.
     *
     * @var Request
     */
    public static Request $request;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    public static ResponseFactory $responseFactory;

    /**
     * The router.
     *
     * @var Router
     */
    public static Router $router;

    /**
     * The route.
     *
     * @var Route
     */
    public static Route $route;
}
