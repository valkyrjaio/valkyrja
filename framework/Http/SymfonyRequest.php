<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Request as RequestContract;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SymfonyRequest
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class SymfonyRequest extends Request implements RequestContract
{
}
