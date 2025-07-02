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

namespace Valkyrja\Tests\Unit\Dispatcher;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Data\CallableDispatch;
use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\ConstantDispatch;
use Valkyrja\Dispatcher\Data\GlobalVariableDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Dispatcher\Data\PropertyDispatch;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatchClass;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;

use function count;
use function method_exists;
use function microtime;

use const PHP_VERSION;

/**
 * Test the dispatcher.
 *
 * @author Melech Mizrachi
 */
class DispatcherTest extends TestCase
{
    /**
     * A valid constant.
     *
     * @var string
     */
    public const string VALID_CONSTANT = 'test';

    /**
     * A valid static property.
     *
     * @var string
     */
    public static string $validStaticProperty = 'test';

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
    public string|null $validPropertyNull = null;

    /**
     * The container to test with.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The class to test with.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * A valid static method.
     *
     * @param string|null $arg [optional] An argument
     *
     * @return string
     */
    public static function validStaticMethod(string|null $arg = null): string
    {
        return 'test' . ($arg ?? '');
    }

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container  = new Container();
        $this->dispatcher = new Dispatcher($this->container);

        $this->container->setSingleton(self::class, $this);
    }

    /**
     * A valid method.
     *
     * @param string|null $arg [optional] An argument
     *
     * @return string
     */
    public function validMethod(string|null $arg = null): string
    {
        return 'test' . ($arg ?? '');
    }

    public function testContract(): void
    {
        self::assertTrue(method_exists(Contract::class, 'dispatch'));
    }

    /**
     * Test the dispatchClassMethod method.
     *
     * @return void
     */
    public function testDispatchClassMethod(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validMethod');

        self::assertSame($this->validMethod(), $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodWithArgs(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validMethod');

        self::assertSame($this->validMethod('test'), $this->dispatcher->dispatch($dispatch, ['test']));
    }

    /**
     * Test the dispatchClassMethod method with arguments with a dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodWithDispatchArg(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validMethod');

        self::assertSame(
            $this->validMethod($this->validMethod()),
            $this->dispatcher->dispatch($dispatch, [$dispatch])
        );
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodStatic(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validStaticMethod', isStatic: true);

        self::assertSame(self::validStaticMethod(), $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch and arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodStaticWithArgs(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validStaticMethod', isStatic: true);

        self::assertSame(
            self::validStaticMethod('test'),
            $this->dispatcher->dispatch($dispatch, ['test'])
        );
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch and arguments with a dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodStaticWithDispatchArg(): void
    {
        $dispatch = new MethodDispatch(class: self::class, method: 'validStaticMethod', isStatic: true);

        self::assertSame(
            self::validStaticMethod(self::validStaticMethod()),
            $this->dispatcher->dispatch($dispatch, [$dispatch])
        );
    }

    /**
     * Test the dispatchClassProperty method.
     *
     * @return void
     */
    public function testDispatchClassProperty(): void
    {
        $dispatch = new PropertyDispatch(class: self::class, property: 'validProperty');

        self::assertSame($this->validProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassProperty method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassPropertyStatic(): void
    {
        $dispatch = new PropertyDispatch(class: self::class, property: 'validStaticProperty', isStatic: true);

        self::assertSame(self::$validStaticProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchCallable method with a null dispatch return.
     *
     * @return void
     */
    public function testDispatchCallableNullDispatchReturn(): void
    {
        $dispatch = new PropertyDispatch(class: self::class, property: 'validPropertyNull');

        self::assertNull($this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClass method.
     *
     * @return void
     */
    public function testDispatchClass(): void
    {
        $dispatch = new ClassDispatch(class: self::class, arguments: ['test']);

        self::assertInstanceOf(self::class, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClass method with arguments.
     *
     * @throws Exception
     *
     * @return void
     */
    public function testDispatchClassWithArgs(): void
    {
        $dispatch = new ClassDispatch(class: InvalidDispatcherClass::class);

        $app = $this->createMock(Application::class);

        self::assertInstanceOf(
            InvalidDispatcherClass::class,
            $this->dispatcher->dispatch(
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
        $dispatch = new ClassDispatch(class: self::class);

        self::assertInstanceOf(self::class, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchFunction method.
     *
     * @return void
     */
    public function testDispatchFunction(): void
    {
        $dispatch = new CallableDispatch(callable: 'microtime');

        self::assertTrue(microtime() <= $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchFunction method with arguments.
     *
     * @return void
     */
    public function testDispatchFunctionWithArgs(): void
    {
        $array    = ['foo', 'bar'];
        $dispatch = new CallableDispatch(callable: 'count');

        self::assertSame(count($array), $this->dispatcher->dispatch($dispatch, [$array]));
    }

    /**
     * Test the dispatchClosure method.
     *
     * @return void
     */
    public function testDispatchClosure(): void
    {
        $dispatch = new CallableDispatch(callable: static fn () => 'test');

        self::assertSame('test', $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClosure method with arguments.
     *
     * @return void
     */
    public function testDispatchClosureWithArgs(): void
    {
        $array    = ['foo', 'bar'];
        $dispatch = new CallableDispatch(callable: static fn (array $array) => count($array));

        self::assertSame(count($array), $this->dispatcher->dispatch($dispatch, [$array]));
    }

    /**
     * Test the dispatchConstant method for a global constant.
     *
     * @return void
     */
    public function testDispatchGlobalConstant(): void
    {
        $dispatch = new ConstantDispatch(constant: 'PHP_VERSION');

        self::assertSame(PHP_VERSION, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchConstant method for a class constant.
     *
     * @return void
     */
    public function testDispatchClassConstant(): void
    {
        $dispatch = new ConstantDispatch(constant: 'VALID_CONSTANT', class: self::class);

        self::assertSame(self::VALID_CONSTANT, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchVariable method for a global variable.
     *
     * @return void
     */
    public function testDispatchVariable(): void
    {
        $dispatch = new GlobalVariableDispatch(variable: '_GET');

        self::assertSame($_GET, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchCallable method.
     *
     * @return void
     */
    public function testDispatchCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = new InvalidDispatchClass();

        $this->dispatcher->dispatch($dispatch);
    }

    public function testDependencies(): void
    {
        $container  = new Container();
        $container2 = new Container();
        $dispatcher = new Dispatcher($container);

        $container->bind(ServiceClass::class, ServiceClass::class);
        $container->setSingleton(Container::class, $container);

        $dispatch = new ClassDispatch(class: ServiceClass::class, dependencies: [Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertInstanceOf(ServiceClass::class, $result);

        $dispatch = new MethodDispatch(class: ServiceClass::class, method: 'make', isStatic: true, dependencies: [Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertInstanceOf(ServiceClass::class, $result);

        $dispatch = new CallableDispatch(callable: [ServiceClass::class, 'make'], dependencies: [Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertInstanceOf(ServiceClass::class, $result);

        $dispatch = new MethodDispatch(class: ServiceClass::class, method: 'getContainer', dependencies: [Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertSame($container, $result);

        $container
            // ->withContext(ServiceClass::class, 'make')
            ->setSingleton(Container::class, $container2);

        $dispatch = new MethodDispatch(class: ServiceClass::class, method: 'make', isStatic: true, dependencies: [Container::class]);

        /** @var ServiceClass $result */
        $result = $dispatcher->dispatch($dispatch);

        self::assertSame($container2, $result->getContainer());

        $dispatch = new CallableDispatch(callable: [ServiceClass::class, 'make'], dependencies: [Container::class]);

        /** @var ServiceClass $result */
        $result = $dispatcher->dispatch($dispatch);

        self::assertSame($container2, $result->getContainer());
    }
}
