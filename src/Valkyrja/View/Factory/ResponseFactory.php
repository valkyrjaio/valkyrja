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
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactoryContract;
use Valkyrja\Http\Message\Factory\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\View\Factory\Contract\ResponseFactory as Contract;
use Valkyrja\View\Renderer\Contract\Renderer;
use Valkyrja\View\Renderer\PhpRenderer;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    public function __construct(
        protected HttpMessageResponseFactoryContract $responseFactory = new HttpMessageResponseFactory(),
        protected Renderer $renderer = new PhpRenderer('resources/views')
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createResponseFromView(
        string $template,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): Response {
        $content = $this->renderer->createTemplate($template, $data ?? [])->render();

        return $this->responseFactory->createResponse($content, $statusCode, $headers);
    }
}
