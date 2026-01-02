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

namespace Valkyrja\Container\Throwable\Exception;

use Throwable;

class InvalidReferenceException extends InvalidArgumentException
{
    /**
     * @param class-string $id The invalid reference class name
     */
    public function __construct(string $id, int $code = 0, Throwable|null $previous = null)
    {
        $message = "Service with `$id` not found";

        parent::__construct($message, $code, $previous);
    }
}
