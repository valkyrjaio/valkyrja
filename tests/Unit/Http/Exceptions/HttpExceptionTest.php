<?php

namespace Valkyrja\Tests\Unit\Http\Exceptions;

use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\ResponseCode;

/**
 * Test the HttpException class.
 *
 * @author Melech Mizrachi
 */
class HttpExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var \Valkyrja\Http\Exceptions\HttpException
     */
    protected $exception;

    /**
     * Get the exception.
     *
     * @return \Valkyrja\Http\Exceptions\HttpException
     */
    protected function getException(): HttpException
    {
        return $this->exception ?? $this->exception = new HttpException();
    }

    /**
     * Test the construction of a new HttpException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, $this->getException() instanceof HttpException);
    }

    /**
     * Test the getStatusCode method.
     *
     * @return void
     */
    public function testGetStatusCode(): void
    {
        $this->assertEquals(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $this->getException()->getStatusCode());
    }

    /**
     * Test the getHeaders method.
     *
     * @return void
     */
    public function testGetHeaders(): void
    {
        $this->assertEquals([], $this->getException()->getHeaders());
    }
}
