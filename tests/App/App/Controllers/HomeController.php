<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\App\App\Controllers;

use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceAlias;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\View\View;
use Valkyrja\Http\Controller;
use Valkyrja\Routing\Route;

/**
 * Class HomeController.
 *
 * @Route(path = '/', name = 'home')
 * @Route(path = '/test', name = 'home.test')
 * @Service(id = App\Controllers\HomeController)
 * @ServiceAlias(id = App\Controllers\HomeController, name = 'homeController')
 * @ServiceContext(
 *     id = Valkyrja\Contracts\Application,
 *     contextClass = App\Controllers\HomeController,
 *     contextMethod = 'getApplication',
 *     static = true
 * )
 */
class HomeController extends Controller
{
    /**
     * The applications.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * Property routing example.
     *
     * @var string
     *
     * @Route(path = '/property', name = 'property')
     */
    public $propertyRouting = 'Property Routing Example';

    /**
     * HomeController constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Welcome action.
     * - Example of a view being returned.
     *
     * @return \Valkyrja\Contracts\View\View
     *
     * @Route(path = '/', name = 'welcome')
     */
    public function welcome(): View
    {
        return view('index')->withoutLayout();
    }

    /**
     * Application version action.
     * - Example of string being returned.
     *
     * @return string
     *
     * @Route(path = '/version', name = 'version', requestMethods = [[GET | POST | HEAD]])
     */
    public function version(): string
    {
        return $this->app->version();
    }

    /**
     * Homepage action.
     * - Example with multiple routes to the same action.
     *
     * @param Application $application The application (Dependency injection example)
     * @param int         $page        The current page
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @Route(path = '/home[/page/{id:num}]', name = 'home', dynamic = true)
     */
    public function home(Application $application, int $page = 1): Response
    {
        return response(view('home/home',
                [
                    'app'  => $application,
                    'page' => $page,
                ])
        );
    }

    /**
     * Service context application test.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function getApplication(): Application
    {
        return app();
    }
}
