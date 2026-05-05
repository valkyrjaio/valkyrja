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

namespace Valkyrja\View\Factory;

use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Factory\ResponseFactory;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\PhpRenderer;

class ViewResponseFactory implements ViewResponseFactoryContract
{
    public function __construct(
        protected ResponseFactoryContract $responseFactory = new ResponseFactory(),
        protected RendererContract $renderer = new PhpRenderer('resources/views')
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createResponseFromView(
        string $template,
        array $data = [],
        StatusCode $statusCode = StatusCode::OK,
        HeaderCollectionContract|null $headers = null
    ): ResponseContract {
        $content = $this->renderer->createTemplate($template, $data)->render();

        return $this->responseFactory->createResponse($content, $statusCode, $headers);
    }
}
