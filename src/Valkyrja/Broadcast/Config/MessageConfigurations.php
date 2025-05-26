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

namespace Valkyrja\Broadcast\Config;

/**
 * Class MessageConfigurations.
 *
 * @author Melech Mizrachi
 */
class MessageConfigurations
{
    public function __construct(
        public DefaultMessageConfiguration|null $default = null,
    ) {
    }
}
