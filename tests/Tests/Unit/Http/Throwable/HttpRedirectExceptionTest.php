<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Throwable;

use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectResponseException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HttpRedirectException class.
 */
final class HttpRedirectExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var HttpRedirectResponseException
     */
    protected HttpRedirectResponseException $exception;

    /**
     * Test the construction of a new HttpRedirectException instance.
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->getException() instanceof HttpRedirectResponseException);
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
    protected function getException(): HttpRedirectResponseException
    {
        return $this->exception ?? $this->exception = new HttpRedirectResponseException();
    }
}
