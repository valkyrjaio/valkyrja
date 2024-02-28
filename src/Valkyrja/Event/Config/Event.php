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

namespace Valkyrja\Event\Config;

use Valkyrja\Event\Config\Config as Model;
use Valkyrja\Event\Constants\ConfigValue;

use function Valkyrja\cachePath;
use function Valkyrja\eventsPath;

/**
 * Class Event.
 */
class Event extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->filePath      = eventsPath('default.php');
        $this->cacheFilePath = cachePath('events.php');
    }
}
