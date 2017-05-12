<?php

namespace Valkyrja\Tests\Unit\Debug;

use ErrorException;
use Exception;
use PHPUnit\Framework\TestCase;
use Valkyrja\Debug\Debug;
use Valkyrja\Debug\ErrorHandler;

/**
 * Test the error handler class.
 *
 * @author Melech Mizrachi
 */
class ErrorHandlerTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Debug\ErrorHandler
     */
    protected $class;

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Debug::enable(E_ALL, true);
        $this->class = new ErrorHandler();
    }

    /**
     * Test the handleError method.
     *
     * @return void
     */
    public function testHandleError(): void
    {
        try {
            $this->class->handleError(1, 'message');
        } catch (Exception $exception) {
            $this->assertEquals(ErrorException::class, get_class($exception));
        }
    }

    /**
     * Test the handleError method's return.
     *
     * @return void
     */
    public function testHandleErrorReturn(): void
    {
        error_reporting(0);
        $return = $this->class->handleError(-1, 'message') ?? null;

        $this->assertEquals(null, $return);
    }

    /**
     * Test the fatalExceptionFromError method.
     *
     * @return void
     */
    public function testFatalExceptionFromError(): void
    {
        $errorException = $this->class->fatalExceptionFromError(
            [
                'message' => 'test',
                'type'    => 1,
                'file'    => 'test',
                'line'    => 1,
            ]
        );

        $this->assertEquals(true, $errorException instanceof ErrorException);
    }
}
