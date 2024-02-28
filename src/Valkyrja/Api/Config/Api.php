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

namespace Valkyrja\Api\Config;

use Valkyrja\Api\Config\Config as Model;
use Valkyrja\Api\Constants\ConfigValue;

/**
 * Class Api.
 */
class Api extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);
    }
}
