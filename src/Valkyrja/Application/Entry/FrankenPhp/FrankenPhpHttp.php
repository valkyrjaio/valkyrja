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

namespace Valkyrja\Application\Entry\FrankenPhp;

use Throwable;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Abstract\WorkerHttp;

use function frankenphp_handle_request;
use function gc_collect_cycles;

class FrankenPhpHttp extends WorkerHttp
{
    /**
     * Run the FrankenPHP app.
     *
     * @see https://frankenphp.dev/docs/worker/
     */
    public static function run(HttpConfig $config): void
    {
        $app = static::bootstrap(
            config: $config,
        );

        $container = $app->getContainer();
        $data      = $container->getData();

        // Handler outside the loop for better performance (doing less work)
        $handler = static function () use ($app, $data): void {
            try {
                static::handle($app, $data, static::getRequest());
            } catch (Throwable) {
                // Currently not handled
            }
        };

        $maxRequests = static::getMaxRequests();

        for ($nbRequests = 0; $maxRequests === 0 || $nbRequests < $maxRequests; $nbRequests++) {
            $keepRunning = static::handleFrankenPhpRequest($handler);

            // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
            gc_collect_cycles();

            if (! $keepRunning) {
                break;
            }
        }
    }

    /**
     * Get the maximum number of requests to handle before the worker stops.
     *
     * A value of 0 means the worker handles requests indefinitely.
     */
    public static function getMaxRequests(): int
    {
        /** @var int|string $maxRequests */
        $maxRequests = $_SERVER['MAX_REQUESTS'] ?? 0;

        return (int) $maxRequests;
    }

    /**
     * Handle a single request through the FrankenPHP runtime.
     *
     * Returns whether the worker should keep running.
     *
     * @codeCoverageIgnore The FrankenPHP runtime function is unavailable outside the worker runtime.
     */
    public static function handleFrankenPhpRequest(callable $handler): bool
    {
        return frankenphp_handle_request($handler);
    }
}
