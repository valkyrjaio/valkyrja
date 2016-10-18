<?php

namespace App\Controllers;

use Valkyrja\Application;
use App\Models\User;

/**
 * Class HomeController
 *
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * HomeController constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Homepage action.
     */
    public function index()
    {
        $this->user->id = 1;

        return response('Home Here');
    }

    /**
     * Paged homepage results.
     *
     * @param int         $page
     * @param Application $application
     */
    public function paged(Application $application, $page)
    {
        $this->user->id = $page;

        dd('Home Paged Here', $this->user, $application);
    }
}
