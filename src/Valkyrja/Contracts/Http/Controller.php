<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

use Valkyrja\Routing\Route;

/**
 * Interface Controller.
 *
 *
 * @author  Melech Mizrachi
 */
interface Controller
{
    /**
     * Before any action is called.
     *
     * @param string                  $method The method called
     * @param \Valkyrja\Routing\Route $route  The route
     *
     * @return void
     */
    public function before(string $method, Route $route): void;

    /**
     * After any action is called.
     *
     * @param string $method   The method called
     * @param mixed  $dispatch The dispatch
     *
     * @return void
     */
    public function after(string $method, &$dispatch): void;
}
