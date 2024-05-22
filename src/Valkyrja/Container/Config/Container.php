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

namespace Valkyrja\Container\Config;

use Valkyrja\Container\Config as Model;
use Valkyrja\Container\Constant\ConfigValue;

use function Valkyrja\cachePath;
use function Valkyrja\servicesPath;

/**
 * Class Container.
 */
class Container extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->providers     = array_merge(ConfigValue::PROVIDERS, []);
        $this->devProviders  = array_merge(ConfigValue::DEV_PROVIDERS, []);
        $this->filePath      = servicesPath('default.php');
        $this->cacheFilePath = cachePath('container.php');
    }
}
