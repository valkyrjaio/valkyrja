<?php

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
use Valkyrja\Http\Enums\StatusCode;

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
    protected $uri;

    /**
     * HttpException constructor.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param int        $statusCode [optional] The status code to use
     * @param string     $uri        [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param int        $code       [optional] The Exception code
     */
    public function __construct(
        int $statusCode = StatusCode::FOUND,
        string $uri = null,
        ?Exception $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        $this->uri = $uri ?? '/';

        parent::__construct($statusCode, 'Redirect', $previous, $headers, $code);
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
