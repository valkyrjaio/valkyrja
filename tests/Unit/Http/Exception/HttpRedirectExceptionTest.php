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

use Valkyrja\Http\Message\Exception\HttpRedirectException;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the HttpRedirectException class.
 *
 * @author Melech Mizrachi
 */
class HttpRedirectExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var HttpRedirectException
     */
    protected HttpRedirectException $exception;

    /**
     * Test the construction of a new HttpRedirectException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->getException() instanceof HttpRedirectException);
    }

    /**
     * Test the getUri method.
     *
     * @return void
     */
    public function testGetUri(): void
    {
        self::assertSame('/', (string) $this->getException()->getUri());
    }

    /**
     * Get the exception.
     *
     * @return HttpRedirectException
     */
    protected function getException(): HttpRedirectException
    {
        return $this->exception ?? $this->exception = new HttpRedirectException();
    }
}
