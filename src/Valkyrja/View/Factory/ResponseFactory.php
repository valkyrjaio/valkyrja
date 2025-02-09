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
use Valkyrja\View\Contract\View;
use Valkyrja\View\Factory\Contract\ResponseFactory as Contract;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    public function __construct(
        protected HttpMessageResponseFactory $responseFactory = new \Valkyrja\Http\Message\Factory\ResponseFactory(),
        protected View $view = new \Valkyrja\View\View()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createResponseFromView(
        string $template,
        ?array $data = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): Response {
        $content = $this->view->render($template, $data ?? []);

        return $this->responseFactory->createResponse($content, $statusCode, $headers);
    }
}
