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

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory as Contract;
use Valkyrja\Http\Routing\Url\Contract\Url;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    public function __construct(
        protected HttpMessageResponseFactory $responseFactory,
        protected Url $url
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createRouteRedirectResponse(
        string $name,
        ?array $data = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): RedirectResponse {
        $url = $this->url->getUrl($name, $data);

        return $this->responseFactory->createRedirectResponse($url, $statusCode, $headers);
    }
}
