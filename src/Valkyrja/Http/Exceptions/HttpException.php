<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
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
     * @param int       $statusCode  [optional] The status code to use
     * @param string    $message     [optional] The Exception message to throw
     * @param Exception $previous    [optional] The previous exception used for
     *                               the exception chaining
     * @param array     $headers     [optional] The headers to send
     * @param int       $code        [optional] The Exception code
     * @param Response  $response    [optional] The Response to send
     */
    public function __construct(
        int $statusCode = StatusCode::INTERNAL_SERVER_ERROR,
        string $message = '',
        Exception $previous = null,
        array $headers = [],
        int $code = 0,
        Response $response = null
    ) {
        $this->statusCode = $statusCode;
        $this->headers    = $headers;
        $this->response   = $response;

        $this->setDefaultResponse();

        parent::__construct($message, $code, $previous);
    }

    /**
     * Set the default response.
     */
    protected function setDefaultResponse(): void
    {
        $view     = view();
        $template = 'errors/' . $this->statusCode;

        // If no response has been set and there is a template with the error code
        if (null === $this->response && file_exists($view->getTemplateDir($template . $view->getFileExtension()))) {
            try {
                // Set the response as the error template
                $this->response = response($this->getDefaultView($template)->render());
            } catch (Exception $exception) {
            }
        }
    }

    /**
     * Get the default view from a given template.
     *
     * @param string $template The template to use
     *
     * @return View
     */
    protected function getDefaultView(string $template): View
    {
        return view($template);
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
