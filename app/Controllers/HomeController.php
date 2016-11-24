<?php

namespace App\Controllers;

use Valkyrja\Contracts\Application;

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
     * Homepage action.
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function index()
    {
        return view('home/home');
    }

    /**
     * Paged homepage results.
     *
     * @param Application $application
     * @param int         $page
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function indexWithParam(Application $application, $page)
    {
        return view('home/home',
            [
                'app'  => $application,
                'page' => $page,
            ]);
    }
}
