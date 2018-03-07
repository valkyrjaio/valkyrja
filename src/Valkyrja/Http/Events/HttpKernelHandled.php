<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Events;

use Valkyrja\Model\Model;

/**
 * Class HttpKernelTerminate.
 */
class HttpKernelHandled extends Model
{
    protected $request;
    protected $response;
}
