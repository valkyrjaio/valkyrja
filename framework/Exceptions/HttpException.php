<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Exceptions;

use Valkyrja\Contracts\Exceptions\HttpException as HttpExceptionContract;

/**
 * Class Exception
 *
 * @package Valkyrja\Exceptions
 *
 * @author  Melech Mizrachi
 */
class HttpException extends \RuntimeException implements HttpExceptionContract
{
    /**
     * The status code for this exception.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * The headers for this exception.
     *
     * @var array
     */
    protected $headers;

    /**
     * The view for this exception.
     *
     * @var string
     */
    protected $view;

    /**
     * @inheritdoc
     */
    public function __construct(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->view = $view;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @inheritdoc
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function getView()
    {
        return $this->view;
    }
}
