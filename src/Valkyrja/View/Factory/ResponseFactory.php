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

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\View\Contract\Renderer;
use Valkyrja\View\Factory\Contract\ResponseFactory as Contract;
use Valkyrja\View\PhpRenderer;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    public function __construct(
        protected HttpMessageResponseFactory $responseFactory = new \Valkyrja\Http\Message\Factory\ResponseFactory(),
        protected Renderer $renderer = new PhpRenderer('resources/views')
    ) {
    }

    /**
     * @inheritDoc
     */
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
