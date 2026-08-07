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

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Throwable\Exception\HttpNotFoundResponseException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the NotFoundHttpException class.
 */
final class NotFoundHttpExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var HttpNotFoundResponseException
     */
    protected HttpNotFoundResponseException $exception;

    /**
     * Test the construction of a new NotFoundHttpException instance.
     */
    public function testConstruct(): void
    {
        self::assertSame(StatusCode::NOT_FOUND, $this->getException()->getStatusCode());
    }

    /**
     * Get the exception.
     */
    protected function getException(): HttpNotFoundResponseException
    {
        return $this->exception ?? $this->exception = new HttpNotFoundResponseException();
    }
}
