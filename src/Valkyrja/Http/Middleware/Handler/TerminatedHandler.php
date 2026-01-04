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

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;

/**
 * @extends Handler<TerminatedMiddlewareContract>
 */
class TerminatedHandler extends Handler implements TerminatedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function terminated(ServerRequestContract $request, ResponseContract $response): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->terminated($request, $response, $this);
        }
    }
}
