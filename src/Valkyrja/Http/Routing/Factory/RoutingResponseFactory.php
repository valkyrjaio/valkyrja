<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Factory;

use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\Contract\RoutingResponseFactoryContract;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;

class RoutingResponseFactory implements RoutingResponseFactoryContract
{
    public function __construct(
        protected ResponseFactoryContract $responseFactory,
        protected UrlContract $url
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createRouteRedirectResponse(
        string $name,
        array $data = [],
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): RedirectResponseContract {
        $url = $this->url->getUrl($name, $data);

        return $this->responseFactory->createRedirectResponse($url, $statusCode, $headers);
    }
}
