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
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler as Contract;

/**
 * Class TerminatedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<TerminatedMiddleware>
 */
class TerminatedHandler extends Handler implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function terminated(ServerRequest $request, Response $response): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->terminated($request, $response, $this);
        }
    }
}
