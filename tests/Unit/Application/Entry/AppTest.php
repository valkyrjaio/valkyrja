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

namespace Valkyrja\Tests\Unit\Application\Entry;

use Throwable;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Entry\App;
use Valkyrja\Application\Env;
use Valkyrja\Application\Exception\RuntimeException;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CliCollection;
use Valkyrja\Cli\Routing\Data as CliData;
use Valkyrja\Container\Data as ContainerData;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Event\Data as EventData;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Collection\Contract\Collection as HttpCollection;
use Valkyrja\Http\Routing\Data as HttpData;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Exiter;
use Valkyrja\Support\Microtime;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

use function defined;

use const LOCK_EX;

/**
 * Test the App service.
 *
 * @author Melech Mizrachi
 */
class AppTest extends TestCase
{
    protected static bool $cliCalled  = false;
    protected static bool $httpCalled = false;

    public static function httpCallback(): Response
    {
        self::$httpCalled = true;

        return new Response();
    }

    public static function cliCallback(): Output
    {
        self::$cliCalled = true;

        return new Output();
    }

    /**
     * Test the appStart method.
     */
    public function testAppStart(): void
    {
        Microtime::freeze();

        $time = Microtime::get();

        App::appStart();

        self::assertTrue(defined('APP_START'));
        self::assertSame($time, APP_START);

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
        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestConfigTest.php';
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
        $env  = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestDataTest.php';
        };
        $data = new Data();
        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

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

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, '', LOCK_EX);

        App::app($env);
    }

    /**
     * Test the app method.
     */
    public function testAppWithBadCache(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid cache');

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestDataTest.php';
        };
        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        file_put_contents($filepath, serialize(new Config()), LOCK_EX);

        try {
            App::app($env);
        } catch (Throwable $e) {
            @unlink($filepath);

            throw $e;
        }
    }

    public function testHttp(): void
    {
        self::$httpCalled = false;

        $_SERVER['REQUEST_URI'] = '/version';

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestHttp.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        $application = App::app($env);
        $container   = $application->getContainer();

        $http = $container->getSingleton(HttpCollection::class);

        $http->add(
            new HttpData\Route(
                path: '/version',
                name: 'version',
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'httpCallback'])
            )
        );
        $data = new Data(container: $container->getData(), http: $http->getData());

        file_put_contents($filepath, serialize($data), LOCK_EX);

        ob_start();
        App::http($dir, $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$httpCalled);

        @unlink($filepath);
        self::$httpCalled = false;
    }

    public function testCli(): void
    {
        self::$cliCalled = false;

        Exiter::freeze();

        $_SERVER['argv'] = [
            'cli',
            'version',
        ];

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = EnvClass::APP_DIR . '/storage/AppTestCli.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $filepath */
        $filepath = $env::APP_CACHE_FILE_PATH;

        $application = App::app($env);
        $container   = $application->getContainer();

        $cli = $container->getSingleton(CliCollection::class);

        $cli->add(
            new CliData\Route(
                name: 'version',
                description: 'test',
                helpText: new Message('test'),
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'cliCallback'])
            )
        );
        $data = new Data(container: $container->getData(), cli: $cli->getData());

        file_put_contents($filepath, serialize($data), LOCK_EX);

        ob_start();
        App::cli($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$cliCalled);

        @unlink($filepath);
        self::$cliCalled = false;
        Exiter::unfreeze();
    }
}
