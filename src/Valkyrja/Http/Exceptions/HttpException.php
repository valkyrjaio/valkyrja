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

namespace Valkyrja\Http\Exceptions;

use Exception;
use RuntimeException;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Response;
use Valkyrja\View\View;

use function file_exists;

/**
 * Class HttpException.
 *
 * @author Melech Mizrachi
 */
class HttpException extends RuntimeException
{
    /**
     * The status code for this exception.
     *
     * @var int
     */
    protected int $statusCode = StatusCode::INTERNAL_SERVER_ERROR;

    /**
     * The headers for this exception.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * The response to send for this exception.
     *
     * @var Response|null
     */
    protected ?Response $response = null;

    /**
     * HttpException constructor.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param int|null       $statusCode [optional] The status code to use
     * @param string|null    $message    [optional] The Exception message to throw
     * @param array|null     $headers    [optional] The headers to send
     * @param Response|null  $response   [optional] The Response to send
     */
    public function __construct(
        int $statusCode = null,
        string $message = null,
        array $headers = null,
        Response $response = null
    ) {
        $this->statusCode = $statusCode ?? StatusCode::INTERNAL_SERVER_ERROR;
        $this->headers    = $headers ?? [];
        $this->response   = $response;

        $this->setDefaultResponse();

        parent::__construct($message ?? '', 0, null);
    }

    /**
     * Set the default response.
     *
     * @return void
     *
     * TODO: Move this logic out to HttpKernel
     */
    protected function setDefaultResponse(): void
    {
        $view     = \Valkyrja\view();
        $template = 'errors/' . $this->statusCode;

        // If no response has been set and there is a template with the error code
        if (null === $this->response && file_exists($view->getDir($template . $view->getFileExtension()))) {
            try {
                // Set the response as the error template
                $this->response = \Valkyrja\response($this->getDefaultView($template)->render());
            } catch (Exception $exception) {
                $this->response = \Valkyrja\response((string) $this->statusCode);
            }
        }
    }

    /**
     * Get the default view from a given template.
     *
     * @param string $template The template to use
     *
     * @return View
     *
     * TODO: Move this logic out to HttpKernel
     */
    protected function getDefaultView(string $template): View
    {
        return \Valkyrja\view($template);
    }

    /**
     * Get the status code for this exception.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the headers set for this exception.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the response for this exception.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
