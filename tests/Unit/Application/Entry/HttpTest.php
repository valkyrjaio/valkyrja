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
use Valkyrja\Application\Entry\Http;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

use const LOCK_EX;

/**
 * Test the Http service.
 */
class HttpTest extends TestCase
{
    protected static bool $runCalled = false;

    public static function routeCallback(): Response
    {
        self::$runCalled = true;

        return new Response();
    }

    public function testHttp(): void
    {
        Http::directory(EnvClass::APP_DIR);

        self::$runCalled = false;

        $_SERVER['REQUEST_URI'] = '/version';

        $env = new class extends EnvClass {
            /** @var bool */
            public const bool APP_DEBUG_MODE = true;
            /** @var non-empty-string */
            public const string APP_CACHE_FILE_PATH = '/storage/AppTestHttp.php';
        };
        /** @var non-empty-string $dir */
        $dir = $env::APP_DIR;
        /** @var non-empty-string $filepath */
        $filepath = EnvClass::APP_DIR . $env::APP_CACHE_FILE_PATH;

        $application = Http::app($env);
        $container   = $application->getContainer();

        $http = $container->getSingleton(CollectionContract::class);

        $http->add(
            new Route(
                path: '/version',
                name: 'version',
                dispatch: MethodDispatch::fromCallableOrArray([self::class, 'routeCallback'])
            )
        );
        $data = new Data(container: $container->getData(), http: $http->getData());

        file_put_contents($filepath, serialize($data), LOCK_EX);

        ob_start();
        Http::run($dir, $env);
        ob_get_clean();

        self::assertTrue(self::$runCalled);

        @unlink($filepath);
        self::$runCalled = false;
    }
}
