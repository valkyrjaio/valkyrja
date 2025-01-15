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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Closure;
use Valkyrja\Container\Contract\Container;

/**
 * Interface Handler.
 *
 * @author Melech Mizrachi
 *
 * @template Middleware
 */
interface Handler
{
    /**
     * @param class-string<Middleware>|Closure(Container): Middleware ...$middleware The middleware to add
     */
    public function add(Closure|string ...$middleware): void;
}
