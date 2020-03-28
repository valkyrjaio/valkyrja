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
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Response;

use function Valkyrja\redirect;

/**
 * Class HttpRedirectException.
 *
 * @author Melech Mizrachi
 */
class HttpRedirectException extends HttpException
{
    /**
     * The uri to redirect to for this exception.
     *
     * @var string
     */
    protected string $uri = '/';

    /**
     * HttpException constructor.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param int       $statusCode [optional] The status code to use
     * @param string    $uri        [optional] The Exception message to throw
     * @param Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array     $headers    [optional] The headers to send
     * @param int       $code       [optional] The Exception code
     * @param Response  $response   [optional] The Response
     */
    public function __construct(
        int $statusCode = StatusCode::FOUND,
        string $uri = null,
        Exception $previous = null,
        array $headers = [],
        int $code = 0,
        Response $response = null
    ) {
        $this->uri = $uri ?? '/';
        // Set a new redirect response if one wasn't passed in
        $response ??= redirect($uri, $statusCode, $headers);

        parent::__construct($statusCode, 'Redirect', $previous, $headers, $code, $response);
    }

    /**
     * Get the uri to redirect to for this exception.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
