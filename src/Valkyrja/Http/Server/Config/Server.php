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

namespace Valkyrja\Http\Server\Config;

use Valkyrja\Http\Server\Config as Model;
use Valkyrja\Http\Server\RequestHandler;

/**
 * Class Middleware.
 */
class Server extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->requestHandler = RequestHandler::class;
    }
}
