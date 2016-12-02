<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Response;

/**
 * Class Controller
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
abstract class Controller implements ControllerContract
{
    /**
     * After any action is called.
     *
     * @param \Valkyrja\Contracts\Http\Response $response
     */
    public function after(Response &$response)
    {
    }
}
