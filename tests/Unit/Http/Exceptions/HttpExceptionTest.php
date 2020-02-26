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

namespace Valkyrja\Tests\Unit\Http\Exceptions;

use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;

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
     * @var HttpException
     */
    protected HttpException $exception;

    /**
     * Get the exception.
     *
     * @return HttpException
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
        $this->assertEquals(StatusCode::INTERNAL_SERVER_ERROR, $this->getException()->getStatusCode());
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
