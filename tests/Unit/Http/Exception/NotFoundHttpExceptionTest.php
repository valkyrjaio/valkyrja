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

use Valkyrja\Http\Message\Exception\NotFoundHttpException;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the NotFoundHttpException class.
 *
 * @author Melech Mizrachi
 */
class NotFoundHttpExceptionTest extends TestCase
{
    /**
     * The exception.
     *
     * @var NotFoundHttpException
     */
    protected NotFoundHttpException $exception;

    /**
     * Test the construction of a new NotFoundHttpException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        self::assertTrue($this->getException() instanceof NotFoundHttpException);
    }

    /**
     * Get the exception.
     *
     * @return NotFoundHttpException
     */
    protected function getException(): NotFoundHttpException
    {
        return $this->exception ?? $this->exception = new NotFoundHttpException();
    }
}
