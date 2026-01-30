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

namespace Valkyrja\Http\Server\Middleware\SendingResponse;

use Override;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;

class NoCacheResponseMiddleware implements SendingResponseMiddlewareContract
{
    /** @var non-empty-string[] */
    protected array $expires = ['Sun, 01 Jan 2014 00:00:00 GMT'];

    /** @var non-empty-string[] */
    protected array $cacheControl = ['no-store, no-cache, must-revalidate', 'post-check=0, pre-check=0'];

    /** @var non-empty-string[] */
    protected array $pragma = ['no-cache'];

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendingResponse(ServerRequestContract $request, ResponseContract $response, SendingResponseHandlerContract $handler): ResponseContract
    {
        return $handler->sendingResponse(
            $request,
            $response
                ->withHeader(HeaderName::EXPIRES, ...$this->expires)
                ->withHeader(HeaderName::CACHE_CONTROL, ...$this->cacheControl)
                ->withHeader(HeaderName::PRAGMA, ...$this->pragma)
        );
    }
}
