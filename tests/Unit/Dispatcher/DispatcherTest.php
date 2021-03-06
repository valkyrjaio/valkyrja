<?php
declare(strict_types=1);

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
use Valkyrja\Application\Application;
use Valkyrja\Container\Config\Config;
use Valkyrja\Container\Constants\ConfigValue;
use Valkyrja\Container\Managers\Container;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Dispatcher\Models\Dispatch;

use function count;
use function microtime;

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
     * @var Dispatcher
     */
    protected Dispatcher $class;

    /**
     * The value to test with.
     *
     * @var string
     */
    protected string $value = 'test';

    /**
     * A valid property.
     *
     * @var string
     */
    public string $validProperty = 'test';

    /**
     * A valid property with null value.
     *
     * @var string|null
     */
    public ?string $validPropertyNull = null;

    /**
     * A valid static property.
     *
     * @var string
     */
    public static string $validStaticProperty = 'test';

    /**
     * Setup the test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->class = new Dispatcher(new Container((array) new Config(ConfigValue::$defaults), true));
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

        self::assertEquals(null, $valid);
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
            self::assertEquals(true, $exception instanceof InvalidMethodException);
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

        self::assertEquals(null, $valid);
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
            self::assertEquals(true, $exception instanceof InvalidPropertyException);
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
                    ->setFunction('\Valkyrja\routeUrl')
            ) ?? null;

        self::assertEquals(null, $valid);
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
            self::assertEquals(true, $exception instanceof InvalidFunctionException);
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
                    ->setClosure(
                        static function () {
                        }
                    )
            ) ?? null;

        self::assertEquals(null, $valid);
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
            self::assertEquals(true, $exception instanceof InvalidDispatchCapabilityException);
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

        self::assertEquals($this->validMethod(), $this->class->dispatchClassMethod($dispatch));
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

        self::assertEquals($this->validMethod('test'), $this->class->dispatchClassMethod($dispatch, ['test']));
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

        self::assertEquals(static::validStaticMethod(), $this->class->dispatchClassMethod($dispatch));
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

        self::assertEquals(static::validStaticMethod('test'), $this->class->dispatchClassMethod($dispatch, ['test']));
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

        self::assertEquals($this->validProperty, $this->class->dispatchClassProperty($dispatch));
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

        self::assertEquals(static::$validStaticProperty, $this->class->dispatchClassProperty($dispatch));
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

        self::assertInstanceOf(static::class, $this->class->dispatchClass($dispatch));
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

        $app = $this->createMock(Application::class);

        self::assertInstanceOf(
            InvalidDispatcherClass::class,
            $this->class->dispatchClass(
                $dispatch,
                [
                    $app,
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

        self::assertInstanceOf(static::class, $this->class->dispatchClass($dispatch));
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

        self::assertEquals(true, microtime() <= $this->class->dispatchFunction($dispatch));
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

        self::assertEquals(count($array), $this->class->dispatchFunction($dispatch, [$array]));
    }

    /**
     * Test the dispatchClosure method.
     *
     * @return void
     */
    public function testDispatchClosure(): void
    {
        $dispatch = (new Dispatch())
            ->setClosure(
                static function () {
                    return 'test';
                }
            );

        self::assertEquals('test', $this->class->dispatchClosure($dispatch));
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
            ->setClosure(
                static function (array $array) {
                    return count($array);
                }
            );

        self::assertEquals(count($array), $this->class->dispatchClosure($dispatch, [$array]));
    }

    /**
     * Test the dispatchCallable method.
     *
     * @return void
     */
    public function testDispatchCallable(): void
    {
        $dispatch = new Dispatch();

        self::assertEquals(null, $this->class->dispatch($dispatch));
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

        self::assertEquals(null, $this->class->dispatch($dispatch));
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

        self::assertEquals(null, $this->class->dispatch($dispatch, [$array]));
    }

    /**
     * Test the dispatchCallable method with arguments in the dispatch.
     *
     * @return void
     */
    public function testDispatchCallableWithArgsInDispatch(): void
    {
        $dispatch = new Dispatch();
        $dispatch = (new Dispatch())->setArguments(['test', $dispatch]);

        self::assertEquals(null, $this->class->dispatch($dispatch));
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

        self::assertEquals(null, $this->class->dispatch($dispatch));
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

        self::assertEquals(null, $this->class->dispatch($dispatch));
    }
}
