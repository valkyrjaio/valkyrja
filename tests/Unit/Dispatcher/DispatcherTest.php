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
use Valkyrja\Container\Config\Container as Config;
use Valkyrja\Container\Container;
use Valkyrja\Container\ContextAwareContainer;
use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Model\Dispatch;
use Valkyrja\Tests\Classes\Container\Service;
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
    public const VALID_CONSTANT = 'test';

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
     * The config to test with.
     *
     * @var Config
     */
    protected Config $config;

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

    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config     = new Config(setup: true);
        $this->container  = new Container($this->config, true);
        $this->dispatcher = new Dispatcher($this->container);

        $this->container->setSingleton(self::class, $this);
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
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validMethod');

        self::assertSame($this->validMethod(), $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodWithArgs(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validMethod');

        self::assertSame($this->validMethod('test'), $this->dispatcher->dispatch($dispatch, ['test']));
    }

    /**
     * Test the dispatchClassMethod method with arguments with a dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodWithDispatchArg(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validMethod');

        self::assertSame(
            $this->validMethod($this->validMethod()),
            $this->dispatcher->dispatch($dispatch, [$dispatch])
        );
    }

    /**
     * Test the dispatchClassMethod method with arguments with a dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodWithNoClassException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setMethod('validMethod');

        self::assertSame(
            $this->validMethod(),
            $this->dispatcher->dispatch($dispatch)
        );
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodStatic(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validStaticMethod')
            ->setStatic();

        self::assertSame(self::validStaticMethod(), $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassMethod method with a static dispatch and arguments.
     *
     * @return void
     */
    public function testDispatchClassMethodStaticWithArgs(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validStaticMethod')
            ->setStatic();

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
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setMethod('validStaticMethod')
            ->setStatic();

        self::assertSame(
            self::validStaticMethod(self::validStaticMethod()),
            $this->dispatcher->dispatch($dispatch, [$dispatch])
        );
    }

    /**
     * Test the dispatchClassMethod method with arguments with a dispatch.
     *
     * @return void
     */
    public function testDispatchClassMethodStaticWithNoClassException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setMethod('validMethod')
            ->setStatic();

        self::assertSame(
            self::validStaticMethod(),
            $this->dispatcher->dispatch($dispatch)
        );
    }

    /**
     * Test the dispatchClassProperty method.
     *
     * @return void
     */
    public function testDispatchClassProperty(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setProperty('validProperty');

        self::assertSame($this->validProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassProperty method.
     *
     * @return void
     */
    public function testDispatchClassPropertyWithNoClassException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setProperty('validProperty');

        self::assertSame($this->validProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassProperty method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassPropertyStatic(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setProperty('validStaticProperty')
            ->setStatic();

        self::assertSame(self::$validStaticProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClassProperty method with a static dispatch.
     *
     * @return void
     */
    public function testDispatchClassPropertyStaticWithNoClassException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setProperty('validStaticProperty')
            ->setStatic();

        self::assertSame(self::$validStaticProperty, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchClass method.
     *
     * @return void
     */
    public function testDispatchClass(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setId(self::class)
            ->setArguments(['test']);

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
        $dispatch = (new Dispatch())
            ->setClass(InvalidDispatcherClass::class)
            ->setId(InvalidDispatcherClass::class);

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
        $dispatch = (new Dispatch())
            ->setClass(self::class);

        self::assertInstanceOf(self::class, $this->dispatcher->dispatch($dispatch));
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
        $dispatch = (new Dispatch())
            ->setFunction('count');

        self::assertSame(count($array), $this->dispatcher->dispatch($dispatch, [$array]));
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
                static fn () => 'test'
            );

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
        $dispatch = (new Dispatch())
            ->setClosure(
                static fn (array $array) => count($array)
            );

        self::assertSame(count($array), $this->dispatcher->dispatch($dispatch, [$array]));
    }

    /**
     * Test the dispatchConstant method for a global constant.
     *
     * @return void
     */
    public function testDispatchGlobalConstant(): void
    {
        $dispatch = (new Dispatch())
            ->setConstant('PHP_VERSION');

        self::assertSame(PHP_VERSION, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchConstant method for a class constant.
     *
     * @return void
     */
    public function testDispatchClassConstant(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setConstant('VALID_CONSTANT');

        self::assertSame(self::VALID_CONSTANT, $this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchVariable method for a global variable.
     *
     * @return void
     */
    public function testDispatchVariable(): void
    {
        $dispatch = (new Dispatch())
            ->setVariable('_GET');

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

        $dispatch = new Dispatch();

        $this->dispatcher->dispatch($dispatch);
    }

    /**
     * Test the dispatchCallable method with a null dispatch return.
     *
     * @return void
     */
    public function testDispatchCallableNullDispatchReturn(): void
    {
        $dispatch = (new Dispatch())
            ->setClass(self::class)
            ->setProperty('validPropertyNull');

        self::assertNull($this->dispatcher->dispatch($dispatch));
    }

    /**
     * Test the dispatchCallable method with arguments.
     *
     * @return void
     */
    public function testDispatchCallableWithArgs(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $array    = ['foo', 'bar'];
        $dispatch = new Dispatch();

        $this->dispatcher->dispatch($dispatch, [$array]);
    }

    /**
     * Test the dispatchCallable method with arguments in the dispatch.
     *
     * @return void
     */
    public function testDispatchCallableWithArgsInDispatch(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = new Dispatch();
        $dispatch = (new Dispatch())->setArguments(['test', $dispatch]);

        $this->dispatcher->dispatch($dispatch);
    }

    /**
     * Test the dispatchCallable method with dependencies.
     *
     * @return void
     */
    public function testDispatchCallableWithDependencies(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setDependencies([self::class]);

        $this->dispatcher->dispatch($dispatch);
    }

    /**
     * Test the dispatchCallable method with a static dispatch and dependencies.
     *
     * @return void
     */
    public function testDispatchCallableStaticWithDependencies(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dispatch = (new Dispatch())
            ->setStatic()
            ->setDependencies([self::class]);

        $this->dispatcher->dispatch($dispatch);
    }

    public function testDependencies(): void
    {
        $container  = new ContextAwareContainer($this->config, true);
        $container2 = new ContextAwareContainer($this->config, true);
        $dispatcher = new Dispatcher($container);

        $container->bind(Service::class, Service::class);
        $container->setSingleton(Container::class, $container);

        $dispatch = (new Dispatch())
            ->setClass(Service::class)
            ->setDependencies([Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertInstanceOf(Service::class, $result);

        $dispatch = (new Dispatch())
            ->setClass(Service::class)
            ->setMethod('make')
            ->setDependencies([Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertInstanceOf(Service::class, $result);

        $dispatch = (new Dispatch())
            ->setClass(Service::class)
            ->setMethod('getContainer')
            ->setDependencies([Container::class]);

        $result = $dispatcher->dispatch($dispatch);

        self::assertSame($container, $result);

        $container
            ->withContext(Service::class, 'make')
            ->setSingleton(Container::class, $container2);

        $dispatch = (new Dispatch())
            ->setClass(Service::class)
            ->setMethod('make')
            ->setDependencies([Container::class]);

        /** @var Service $result */
        $result = $dispatcher->dispatch($dispatch);

        self::assertSame($container2, $result->getContainer());
    }
}
