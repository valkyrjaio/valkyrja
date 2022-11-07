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

use App\Providers\AppServiceProvider;
use Valkyrja\Container\Config\Config as Model;
use Valkyrja\Container\Constants\ConfigValue;

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
    protected function setup(array $properties = null): void
    {
        $this->aliases         = [];
        $this->services        = [];
        $this->contextServices = [];
        $this->providers       = array_merge(
            ConfigValue::PROVIDERS,
            [
                AppServiceProvider::class,
            ]
        );
        $this->devProviders    = array_merge(ConfigValue::DEV_PROVIDERS, []);
        $this->setupFacade     = true;

        $this->filePath       = servicesPath('default.php');
        $this->cacheFilePath  = cachePath('container.php');
        $this->useAnnotations = false;
        $this->useCache       = false;
    }
}
