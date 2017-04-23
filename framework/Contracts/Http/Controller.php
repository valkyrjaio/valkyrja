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

/**
 * Interface Controller
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Controller
{
    /**
     * Before any action is called.
     *
     * @param string $method    The method called
     * @param array  $arguments The arguments
     *
     * @return void
     */
    public function before(string $method, array &$arguments): void;

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
