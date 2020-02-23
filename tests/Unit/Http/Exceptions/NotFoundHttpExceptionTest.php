<?php

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
use Valkyrja\Http\Exceptions\NotFoundHttpException;

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
     * Get the exception.
     *
     * @return \Valkyrja\Http\Exceptions\NotFoundHttpException
     */
    protected function getException(): \Valkyrja\Http\Exceptions\NotFoundHttpException
    {
        return $this->exception ?? $this->exception = new NotFoundHttpException();
    }

    /**
     * Test the construction of a new NotFoundHttpException instance.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertEquals(true, $this->getException() instanceof NotFoundHttpException);
    }
}
