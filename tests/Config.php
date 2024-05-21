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

namespace Valkyrja\Tests;

use Valkyrja\Config\Config\Valkyrja;
use Valkyrja\Config\Constant\ConfigValue;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Valkyrja
{
    public Config $new;

    public function __construct()
    {
        parent::__construct(ConfigValue::$defaults, true);
    }
}
