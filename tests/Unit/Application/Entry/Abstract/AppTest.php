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

namespace Valkyrja\Tests\Unit\Application\Entry\Abstract;

use Throwable;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Entry\Abstract\App;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Throwable\Exception\RuntimeException;
use Valkyrja\Cli\Routing\Data\Data as CliData;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Http\Routing\Data\Data as HttpData;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Time\Microtime;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

use function defined;

use const LOCK_EX;

/**
 * Test the App service.
 */
class AppTest extends TestCase
{
    /**
     * Test the appStart method.
     */
    public function testAppStart(): void
    {
        Microtime::freeze();

        $time = Microtime::get();

        App::appStart();

        self::assertTrue(defined('APP_START'));
        self::assertSame(APP_START, $time);

        Microtime::unfreeze();
    }

    /**
     * Test the directory method.
     */
    public function testDirectory(): void
    {
        $path = __DIR__;

        App::directory($path);

        self::assertSame($path, Directory::$BASE_PATH);
    }

    /**
     * Test the app method.
     */
    public function testApp(): void
    {
        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestConfigTest.php';
        };

        $application = App::app($env);

        $container = $application->getContainer();

        self::assertTrue($container->has(Config::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());
    }

    /**
     * Test the app method.
     */
    public function testAppWithCache(): void
    {
        App::directory(EnvClass::APP_DIR);

        $env  = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        $data = new Data();
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, serialize($data), LOCK_EX);

        $application = App::app($env);

        $container = $application->getContainer();

        self::assertNotTrue($container->has(Config::class));
        self::assertTrue($container->has(ContainerData::class));
        self::assertTrue($container->has(EventData::class));
        self::assertTrue($container->has(CliData::class));
        self::assertTrue($container->has(HttpData::class));
        self::assertSame($env, $application->getEnv());
        self::assertSame($env, $container->getSingleton(Env::class));
        self::assertSame($env::APP_TIMEZONE, date_default_timezone_get());

        @unlink($filepath);
    }

    /**
     * Test the app method.
     */
    public function testAppWithEmptyCache(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error occurred when retrieving cache file contents');

        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, '', LOCK_EX);

        App::app($env);
    }

    /**
     * Test the app method.
     *
     * @throws Throwable
     */
    public function testAppWithBadCache(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid cache');

        App::directory(EnvClass::APP_DIR);

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, serialize(new Config()), LOCK_EX);

        try {
            App::app($env);
        } catch (Throwable $e) {
            @unlink($filepath);

            throw $e;
        }
    }
}
