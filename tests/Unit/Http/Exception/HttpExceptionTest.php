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

namespace Valkyrja\Tests\Unit\Http\Exception;

use Valkyrja\Http\Message\Constant\StatusCode;
use Valkyrja\Http\Message\Exception\HttpException;
use Valkyrja\Tests\Unit\TestCase;

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
     * Test the construction of a new HttpException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->getException() instanceof HttpException);
    }

    /**
     * Test the getStatusCode method.
     *
     * @return void
     */
    public function testGetStatusCode(): void
    {
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $this->getException()->getStatusCode());
    }

    /**
     * Test the getHeaders method.
     *
     * @return void
     */
    public function testGetHeaders(): void
    {
        self::assertSame([], $this->getException()->getHeaders());
    }

    /**
     * Get the exception.
     *
     * @return HttpException
     */
    protected function getException(): HttpException
    {
        return $this->exception ?? $this->exception = new HttpException();
    }
}
