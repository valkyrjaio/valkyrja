<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Dispatcher;

use Exception;
use PHPUnit\Framework\TestCase;
use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Events\Events;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Test the dispatcher trait.
 *
 * @author Melech Mizrachi
 */
class DispatcherTest extends TestCase
{
    /**
     * The class to test with.
     *
     * @var \Valkyrja\Dispatcher\Dispatcher
     */
    protected $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected $value = 'test';
    /**
     * A valid property.
     *
     * @var string
     */
    public $validProperty = 'test';

    /**
     * A valid static property.
     *
     * @var string
     */
    public static $validStaticProperty = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        /** @var Container $container */
        $container = $this->createMock(Container::class);
        /** @var Events $events */
        $events = $this->createMock(Events::class);

        $this->class = new Dispatcher($container, $events);
    }

    /**
     * A valid method.
     *
     * @param string $arg [optional] An argument
     *
     * @return string
     */
    public function validMethod(string $arg = null): string
    {
        return 'test' . ($arg ?: '');
    }

    /**
     * A valid static method.
     *
     * @param string $arg [optional] An argument
     *
     * @return string
     */
    public static function validStaticMethod(string $arg = null): string
    {
        return 'test' . ($arg ?: '');
    }

    /**
     * Verify a valid class/method dispatch.
     *
     * @return void
     */
    public function testVerifyClassMethod(): void
    {
        $valid = $this->class->verifyClassMethod(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setMethod('validMethod')
            ) ?? null;

        $this->assertEquals(null, $valid);
    }

    /**
     * Verify an invalid class/method dispatch.
     *
     * @return void
     */
    public function testVerifyClassMethodInvalid(): void
    {
        try {
            $this->class->verifyClassMethod(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setMethod('invalidMethod')
            );
        } catch (Exception $exception) {
            $this->assertEquals(true, $exception instanceof InvalidMethodException);
        }
    }

    /**
     * Verify a valid class/property dispatch.
     *
     * @return void
     */
    public function testVerifyClassProperty(): void
    {
        $valid = $this->class->verifyClassProperty(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setProperty('validProperty')
            ) ?? null;

        $this->assertEquals(null, $valid);
    }

    /**
     * Verify an invalid class/property dispatch.
     *
     * @return void
     */
    public function testVerifyClassPropertyInvalid(): void
    {
        try {
            $this->class->verifyClassProperty(
                (new Dispatch())
                    ->setClass(self::class)
                    ->setProperty('invalidProperty')
            );
        } catch (Exception $exception) {
            $this->assertEquals(true, $exception instanceof InvalidPropertyException);
        }
    }

    /**
     * Verify a valid function dispatch.
     *
     * @return void
     */
    public function testVerifyFunction(): void
    {
        $valid = $this->class->verifyFunction(
                (new Dispatch())
                    ->setFunction('routeUrl')
            ) ?? null;

        $this->assertEquals(null, $valid);
    }

    /**
     * Verify an invalid function dispatch.
     *
     * @return void
     */
    public function testVerifyFunctionInvalid(): void
    {
        try {
            $this->class->verifyFunction(
                (new Dispatch())
                    ->setFunction('invalidFunction')
            );
        } catch (Exception $exception) {
            $this->assertEquals(true, $exception instanceof InvalidFunctionException);
        }
    }

    /**
     * Verify a valid closure dispatch.
     *
     * @return void
     */
    public function testVerifyClosure(): void
    {
        $valid = $this->class->verifyClosure(
                (new Dispatch())
                    ->setClosure(function () {
                    })
            ) ?? null;

        $this->assertEquals(null, $valid);
    }

    /**
     * Verify an invalid closure dispatch.
     *
     * @return void
     */
    public function testVerifyClosureInvalid(): void
    {
        try {
            $this->class->verifyClosure(
                new Dispatch()
            );
        } catch (Exception $exception) {
            $this->assertEquals(true, $exception instanceof InvalidClosureException);
        }
    }

    /**
     * Verify a valid dispatch.
     *
     * @return void
     */
    public function testVerifyDispatch(): void
    {
        $valid = $this->class->verifyDispatch(
                (new Dispatch())
                    ->setClosure(function () {
                    })
            ) ?? null;

        $this->assertEquals(null, $valid);
    }

    /**
     * Verify an invalid dispatch.
     *
     * @return void
     */
    public function testVerifyDispatchInvalid(): void
    {
        try {
            $this->class->verifyDispatch(
                new Dispatch()
            );
        } catch (Exception $exception) {
            $this->assertEquals(true, $exception instanceof InvalidDispatchCapabilityException);
        }
    }
}
