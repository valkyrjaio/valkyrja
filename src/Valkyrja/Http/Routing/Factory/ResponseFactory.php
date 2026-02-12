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

namespace Valkyrja\Http\Routing\Factory;

use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;

class ResponseFactory implements ResponseFactoryContract
{
    public function __construct(
        protected HttpMessageResponseFactory $responseFactory,
        protected UrlContract $url
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createRouteRedirectResponse(
        string $name,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): RedirectResponseContract {
        $url = $this->url->getUrl($name, $data);

        return $this->responseFactory->createRedirectResponse($url, $statusCode, $headers);
    }
}
