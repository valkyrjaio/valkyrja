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

namespace Valkyrja\Notification\Config;

use Valkyrja\Notification\Config as Model;
use Valkyrja\Notification\Constant\ConfigValue;

/**
 * Class Notification.
 */
class Notification extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(?array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->notifications = array_merge(ConfigValue::NOTIFICATIONS, []);
    }
}
