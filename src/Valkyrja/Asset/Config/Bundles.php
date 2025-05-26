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

namespace Valkyrja\Asset\Config;

/**
 * Class Bundles.
 *
 * @author Melech Mizrachi
 */
class Bundles
{
    public function __construct(
        public DefaultBundle|null $default = null,
    ) {
    }
}
