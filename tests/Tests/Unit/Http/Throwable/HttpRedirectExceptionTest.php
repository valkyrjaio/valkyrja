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

namespace Valkyrja\Tests\Unit\Http\Throwable;

use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HttpRedirectException class.
 */
final class HttpRedirectExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var HttpRedirectException
     */
    protected HttpRedirectException $exception;

    /**
     * Test the construction of a new HttpRedirectException instance.
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->getException() instanceof HttpRedirectException);
    }

    /**
     * Test the getUri method.
     */
    public function testGetUri(): void
    {
        self::assertSame('/', (string) $this->getException()->getUri());
    }

    /**
     * Get the exception.
     */
    protected function getException(): HttpRedirectException
    {
        return $this->exception ?? $this->exception = new HttpRedirectException();
    }
}
