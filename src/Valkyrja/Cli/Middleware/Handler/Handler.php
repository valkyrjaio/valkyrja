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

namespace Valkyrja\Cli\Middleware\Handler;

use Override;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Container\Contract\Container;

use function array_merge;

/**
 * Abstract Class Handler.
 *
 * @author Melech Mizrachi
 *
 * https://psalm.dev/r/7441ba42c3 Weird errors for the template but `of ...` fixes it
 * https://psalm.dev/r/e76d278bf9 __construct gives wrong expects as first of template below instead of correct one from extends. add() is correct, though
 *
 * @template Middleware of InputReceivedMiddleware|CommandMatchedMiddleware|CommandNotMatchedMiddleware|CommandDispatchedMiddleware|ThrowableCaughtMiddleware|ExitedMiddleware
 *
 * @implements Contract\Handler<Middleware>
 */
abstract class Handler implements Contract\Handler
{
    /** @var array<array-key, class-string<Middleware>> */
    protected array $middleware = [];
    /** @var class-string<Middleware>|null */
    protected string|null $next = null;
    /** @var int */
    protected int $index = 0;

    /**
     * @param class-string<Middleware> ...$middleware The middleware
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        string ...$middleware,
    ) {
        $this->middleware = $middleware;

        $this->updateNext();
    }

    /**
     * @param class-string<Middleware> ...$middleware The middleware to add
     */
    #[Override]
    public function add(string ...$middleware): void
    {
        $this->middleware = array_merge($this->middleware, $middleware);

        $this->updateNext();
    }

    /**
     * Get the next middleware in order to continue handling.
     *
     * @param class-string<Middleware> $middleware The middleware to handle
     *
     * @return Middleware
     */
    protected function getMiddleware(string $middleware): object
    {
        /** @var Middleware $item */
        $item = $this->container->get($middleware);

        $this->index++;

        $this->updateNext();

        return $item;
    }

    /**
     * Update the next middleware to use.
     *
     * @return void
     */
    protected function updateNext(): void
    {
        $this->next = $this->middleware[$this->index] ?? null;
    }
}
