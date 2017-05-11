<?php

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
     * @var \Valkyrja\Http\Exceptions\NotFoundHttpException
     */
    protected $exception;

    /**
     * Get the exception.
     *
     * @return \Valkyrja\Http\Exceptions\NotFoundHttpException
     */
    protected function getException(): NotFoundHttpException
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
