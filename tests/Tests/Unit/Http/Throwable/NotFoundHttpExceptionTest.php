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
        self::assertTrue($this->getException() instanceof HttpNotFoundResponseException);
    }

    /**
     * Get the exception.
     */
    protected function getException(): HttpNotFoundResponseException
    {
        return $this->exception ?? $this->exception = new HttpNotFoundResponseException();
    }
}
