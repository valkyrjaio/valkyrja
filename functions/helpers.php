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

namespace Valkyrja;

use function var_dump;

/**
 * Dump the passed variables and die.
 *
 * @param mixed ...$args The arguments to dump
 *
 * @return never
 */
function dd(...$args): never
{
    var_dump($args);

    exit(1);
}
