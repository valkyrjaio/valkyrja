<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Dispatchers;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Cacheables\CacheableRouter;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Helpers\RouteGroup;
use Valkyrja\Routing\Helpers\RouteMethods;
use Valkyrja\Routing\Helpers\RouterHelpers;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Router as RouterContract;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\View;

use function is_array;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements RouterContract
{
    use CacheableRouter;
    use Provides;
    use RouteGroup;
    use RouteMethods;
    use RouterHelpers;

    /**
     * Router constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RouterContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            RouterContract::class,
            new static($app)
        );

        $app->router()->setup();
    }

    /**
     * Get the route collection.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return self::$collection;
    }

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function matcher(): Matcher
    {
        return self::$collection->matcher();
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        // Get the route
        $route = $this->getRouteFromRequest($request);

        // Determine if the route is a redirect
        $this->determineRedirectRoute($route);
        // Determine if the route is secure and should be redirected
        $this->determineIsSecureRoute($request, $route);
        // Dispatch the route's before request handled middleware
        $request = $this->routeRequestMiddleware($request, $route);

        // Trigger an event for route matched
        $this->app->events()->trigger(RouteMatched::class, [$route, $request]);
        // Set the found route in the service container
        $this->app->container()->setSingleton(Route::class, $route);

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->app->dispatcher()->dispatch($route, $route->getMatches());
        // Get the response from the dispatch
        $response = $this->getResponseFromDispatch($dispatch);

        // Dispatch the route's before request handled middleware and return the response
        return $this->routeResponseMiddleware($request, $response, $route);
    }

    /**
     * Determine if a route is a redirect.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function determineRedirectRoute(Route $route): void
    {
        // If the route is a redirect and a redirect route is set
        if ($route->isRedirect()) {
            // Throw the redirect to the redirect path
            $this->app->redirect($route->getTo(), $route->getCode())->throw();
        }
    }

    /**
     * Determine if the route should be secure.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return void
     */
    protected function determineIsSecureRoute(Request $request, Route $route): void
    {
        // If the route is secure and the current request is not secure
        if ($route->isSecure() && ! $request->getUri()->isSecure()) {
            // Throw the redirect to the secure path
            $this->app->redirect()->secure($request->getUri()->getPath())->throw();
        }
    }

    /**
     * Dispatch a route's before request handled middleware.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return Request
     */
    protected function routeRequestMiddleware(Request $request, Route $route): Request
    {
        return $this->app->kernel()->requestMiddleware($request, $route->getMiddleware() ?? []);
    }

    /**
     * Get a response from a dispatch.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return Response
     */
    protected function getResponseFromDispatch($dispatch): Response
    {
        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof Response) {
            return $dispatch;
        }

        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof View) {
            return $this->app->response($dispatch->render());
        }

        // If the dispatch is an array, return it as JSON
        if (is_array($dispatch)) {
            return $this->app->json($dispatch);
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->app->response((string) $dispatch);
    }

    /**
     * Dispatch a route's after request handled middleware.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     * @param Route    $route    The route
     *
     * @return Response
     */
    protected function routeResponseMiddleware(Request $request, Response $response, Route $route): Response
    {
        return $this->app->kernel()->responseMiddleware(
            $request,
            $response,
            $route->getMiddleware() ?? []
        );
    }
}
