<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Routing\Controller;

use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

/**
 * Abstract Class Controller.
 *
 * @author Melech Mizrachi
 */
abstract class Controller
{
    public function __construct(
        protected ServerRequestContract $request,
        protected ResponseFactoryContract $responseFactory,
    ) {
    }
}
