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

namespace Valkyrja\Routing\Config;

use Valkyrja\Routing\Config\Config as Model;
use Valkyrja\Routing\Constants\ConfigValue;

use function Valkyrja\cachePath;
use function Valkyrja\routesPath;

/**
 * Class Routing.
 */
class Routing extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->filePath      = routesPath('default.php');
        $this->cacheFilePath = cachePath('routes.php');
    }
}
