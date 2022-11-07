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

namespace Valkyrja\Console\Config;

use Valkyrja\Console\Config\Config as Model;
use Valkyrja\Console\Constants\ConfigValue;

use function Valkyrja\cachePath;
use function Valkyrja\commandsPath;

/**
 * Class Console.
 */
class Console extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->handlers     = [];
        $this->providers    = array_merge(ConfigValue::PROVIDERS, []);
        $this->devProviders = array_merge(ConfigValue::DEV_PROVIDERS, []);
        $this->quiet        = false;

        $this->useAnnotations = false;
        $this->filePath       = commandsPath('default.php');
        $this->cacheFilePath  = cachePath('commands.php');
        $this->useCache       = false;
    }
}
