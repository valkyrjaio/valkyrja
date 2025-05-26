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

namespace Valkyrja\Http\Message\Factory\Data;

/**
 * Class HostPortAccumulator.
 *
 * @author Melech Mizrachi
 */
class HostPortAccumulator
{
    public function __construct(
        public string $host = '',
        public int|null $port = null
    ) {
    }
}
