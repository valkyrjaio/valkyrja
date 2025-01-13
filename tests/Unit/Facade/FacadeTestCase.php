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

namespace Valkyrja\Tests\Unit\Facade;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Container\Container;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Abstract FacadeTestCase.
 *
 * @author Melech Mizrachi
 */
abstract class FacadeTestCase extends TestCase
{
    /** @var class-string */
    protected static string $contract;
    /** @var class-string<ContainerFacade> */
    protected static string $facade;

    protected Container $container;

    /**
     * @return string[][]
     */
    abstract public static function methodsProvider(): array;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $container = new Container();

        ContainerFacade::setContainer($container);

        $this->container->setSingleton(static::$contract, $this->createMock(static::$contract));
    }

    public function testInstance(): void
    {
        self::assertInstanceOf(static::$contract, static::$facade::instance());
    }

    #[DataProvider('methodsProvider')]
    public function testMethods(string $method): void
    {
        self::assertIsCallable([static::$facade::instance(), $method]);
    }
}
