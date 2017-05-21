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
use Valkyrja\Container\Container;
use Valkyrja\Container\Service;
use Valkyrja\Contracts\Application;
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
     * A valid property with null value.
     *
     * @var string
     */
    public $validPropertyNull;

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

        /** @var Application $app */
        $app = $this->createMock(Application::class);
        /** @var Events $events */
        $events = $this->createMock(Events::class);
        /** @var Container $container */
        $container = new Container($app, $events);

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

    /**
     * Test the dispatchClassMethod method.
     *
     * @return void
     */
    public function testDispatchClassMethod(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setMethod('validMethod');

        $this->assertEquals($this->validMethod(), $this->class->dispatchClassMethod($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodWithArgs(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setMethod('validMethod');

        $this->assertEquals($this->validMethod('test'), $this->class->dispatchClassMethod($dispatch, ['test']));
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodStatic(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setMethod('validStaticMethod')
            ->setStatic(true);

        $this->assertEquals(static::validStaticMethod(), $this->class->dispatchClassMethod($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch and arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodStaticWithArgs(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setMethod('validStaticMethod')
            ->setStatic(true);

        $this->assertEquals(static::validStaticMethod('test'), $this->class->dispatchClassMethod($dispatch, ['test']));
    }

    /**
     * Test the dispatchClassProperty method.
     *
     * @return void
     */
    public function testDispatchClassProperty(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setProperty('validProperty');

        $this->assertEquals($this->validProperty, $this->class->dispatchClassProperty($dispatch));
    }

    /**
     * Test the dispatchClassProperty method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassPropertyStatic(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setProperty('validStaticProperty')
            ->setStatic(true);

        $this->assertEquals(static::$validStaticProperty, $this->class->dispatchClassProperty($dispatch));
    }

    /**
     * Test the dispatchClass method.
     *
     * @return void
     */
    public function testDispatchClass(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setId(static::class);

        $this->assertInstanceOf(static::class, $this->class->dispatchClass($dispatch));
    }

    /**
     * Test the dispatchClass method with arguments.
     *
     * @return void
     */
    public function testDispatchClassWithArgs(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(InvalidDispatcherClass::class)
            ->setId(InvalidDispatcherClass::class);

        $events    = $this->createMock(Events::class);
        $container = $this->createMock(\Valkyrja\Contracts\Container\Container::class);

        $this->assertInstanceOf(
            InvalidDispatcherClass::class,
            $this->class->dispatchClass($dispatch,
                [
                    $container,
                    $events,
                ]
            )
        );
    }

    /**
     * Test the dispatchClass method with a class from the container.
     *
     * @return void
     */
    public function testDispatchClassFromContainer(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class);

        $this->assertInstanceOf(static::class, $this->class->dispatchClass($dispatch));
    }

    /**
     * Test the dispatchFunction method.
     *
     * @return void
     */
    public function testDispatchFunction(): void
    {
        $dispatch = (new Dispatch())
            ->setFunction('microtime');

        $this->assertEquals(true, microtime() <= $this->class->dispatchFunction($dispatch));
    }

    /**
     * Test the dispatchFunction method with arguments.
     *
     * @return void
     */
    public function testDispatchFunctionWithArgs(): void
    {
        $array    = ['foo', 'bar'];
        $dispatch = (new Dispatch())
            ->setFunction('count');

        $this->assertEquals(count($array), $this->class->dispatchFunction($dispatch, [$array]));
    }

    /**
     * Test the dispatchClosure method.
     *
     * @return void
     */
    public function testDispatchClosure(): void
    {
        $dispatch = (new Dispatch())
            ->setClosure(function () {
                return 'test';
            });

        $this->assertEquals('test', $this->class->dispatchClosure($dispatch));
    }

    /**
     * Test the dispatchClosure method with arguments.
     *
     * @return void
     */
    public function testDispatchClosureWithArgs(): void
    {
        $array    = ['foo', 'bar'];
        $dispatch = (new Dispatch())
            ->setClosure(function (array $array) {
                return count($array);
            });

        $this->assertEquals(count($array), $this->class->dispatchClosure($dispatch, [$array]));
    }

    /**
     * Test the dispatchCallable method.
     *
     * @return void
     */
    public function testDispatchCallable(): void
    {
        $dispatch = new Dispatch();

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch));
    }

    /**
     * Test the dispatchCallable method with a null dispatch return.
     *
     * @return void
     */
    public function testDispatchCallableNullDispatchReturn(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(static::class)
            ->setProperty('validPropertyNull');

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch));
    }

    /**
     * Test the dispatchCallable method with arguments.
     *
     * @return void
     */
    public function testDispatchCallableWithArgs(): void
    {
        $array    = ['foo', 'bar'];
        $dispatch = new Dispatch();

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch, [$array]));
    }

    /**
     * Test the dispatchCallable method with arguments in the dispatch.
     *
     * @return void
     */
    public function testDispatchCallableWithArgsInDispatch(): void
    {
        $dispatch = new Dispatch();
        $service  = (new Service())
            ->setId(static::class);
        $dispatch = (new Dispatch())
            ->setArguments(['test', $dispatch, $service]);

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch));
    }

    /**
     * Test the dispatchCallable method with dependencies.
     *
     * @return void
     */
    public function testDispatchCallableWithDependencies(): void
    {
        $dispatch = (new Dispatch())
            ->setDependencies([static::class]);

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch));
    }

    /**
     * Test the dispatchCallable method with a static dispatch and dependencies.
     *
     * @return void
     */
    public function testDispatchCallableStaticWithDependencies(): void
    {
        $dispatch = (new Dispatch())
            ->setStatic(true)
            ->setDependencies([static::class]);

        $this->assertEquals(null, $this->class->dispatchCallable($dispatch));
    }
}
