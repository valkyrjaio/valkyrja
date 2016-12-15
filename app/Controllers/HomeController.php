<?php

namespace App\Controllers;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\View\View;

/**
 * Class HomeController
 *
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

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
     *
     * @return \Valkyrja\Contracts\View\View
     *
     * @Route('path' => '/', 'name' => 'welcome')
     */
    public function welcome() : View
    {
        return view('index')->withoutLayout();
    }

    /**
     * Homepage action.
     *
     * @return string
     *
     * @Route('path' => '/version', 'name' => 'version')
     */
    public function version() : string
    {
        return $this->app->version();
    }

    /**
     * Homepage action.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @Route('path' => '/home', 'name' => 'home')
     */
    public function home() : Response
    {
        return response(view('home/home'));
    }

    /**
     * Paged homepage results.
     *
     * @param Application $application
     * @param int         $page
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @Route('path' => '\/home\/(\d+)', 'name' => 'homePaged', 'dynamic' => true)
     */
    public function homePaged(Application $application, $page) : Response
    {
        return response(view('home/home',
            [
                'app'  => $application,
                'page' => $page,
            ])
        );
    }
}
