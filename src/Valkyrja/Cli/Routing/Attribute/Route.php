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

namespace Valkyrja\Cli\Routing\Attribute;

use Attribute;
use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Cli\Routing\Data\Contract\Parameter;
use Valkyrja\Cli\Routing\Data\Route as Model;
use Valkyrja\Dispatch\Data\Contract\MethodDispatch;
use Valkyrja\Dispatch\Data\MethodDispatch as DefaultDispatch;

/**
 * Attribute Route.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends Model
{
    /**
     * @param non-empty-string                            $name                        The name
     * @param non-empty-string                            $description                 The description
     * @param Message                                     $helpText                    The help text
     * @param class-string<CommandMatchedMiddleware>[]    $commandMatchedMiddleware    The command matched middleware
     * @param class-string<CommandDispatchedMiddleware>[] $commandDispatchedMiddleware The command dispatched middleware
     * @param class-string<ThrowableCaughtMiddleware>[]   $throwableCaughtMiddleware   The throwable caught middleware
     * @param class-string<ExitedMiddleware>[]            $exitedMiddleware            The exited middleware
     * @param Parameter[]                                 $parameters                  The parameters
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected Message $helpText,
        protected MethodDispatch $dispatch = new DefaultDispatch(self::class, '__construct'),
        protected array $commandMatchedMiddleware = [],
        protected array $commandDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $exitedMiddleware = [],
        array $parameters = [],
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            helpText: $helpText,
            dispatch: $dispatch,
            commandMatchedMiddleware: $commandMatchedMiddleware,
            commandDispatchedMiddleware: $commandDispatchedMiddleware,
            throwableCaughtMiddleware: $throwableCaughtMiddleware,
            exitedMiddleware: $exitedMiddleware,
            parameters: $parameters,
        );
    }
}
