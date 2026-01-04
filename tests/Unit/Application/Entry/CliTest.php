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

use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Entry\Cli;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Server\Support\Exiter;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

use function restore_error_handler;
use function restore_exception_handler;

use const LOCK_EX;

/**
 * Test the Cli service.
 */
class CliTest extends TestCase
{
    protected static bool $runCalled = false;

    public static function routeCallback(): Output
    {
        self::$runCalled = true;

        return new Output();
    }

    public function testRun(): void
    {
        Cli::directory(EnvClass::APP_DIR);

        self::$runCalled = false;

        Exiter::freeze();

        $_SERVER['argv'] = [
            'cli',
            'version',
        ];

        $env = new class extends EnvClass {
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestCli.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        $application = Cli::app($env);
        $container   = $application->getContainer();

        $cli = $container->getSingleton(CollectionContract::class);

        $cli->add(
            new Route(
                name: 'version',
                description: 'test',
                helpText: new Message('test'),
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'routeCallback'])
            )
        );
        $data = new Data(container: $container->getData(), cli: $cli->getData());

        file_put_contents($filepath, serialize($data), LOCK_EX);

        ob_start();
        Cli::run($dir, $env);
        ob_get_clean();

        restore_error_handler();
        restore_exception_handler();

        self::assertTrue(self::$runCalled);

        @unlink($filepath);
        self::$runCalled = false;
        Exiter::unfreeze();
    }
}
