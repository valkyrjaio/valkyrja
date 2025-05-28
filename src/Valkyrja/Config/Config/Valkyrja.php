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

namespace Valkyrja\Config\Config;

use Valkyrja\Application\Config\App;
use Valkyrja\Config\Config\Config as Model;
use Valkyrja\Console\Config\Console;
use Valkyrja\Orm\Config\Orm;

use function Valkyrja\cachePath;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        /** @var array<string, array<string, mixed>>|null $properties */
        $this->app     = new App($properties['app'] ?? null, true);
        $this->console = new Console($properties['console'] ?? null, true);
        $this->orm     = new Orm($properties['orm'] ?? null, true);

        $this->providers     = [];
        $this->cacheFilePath = cachePath('config.php');
        $this->useCache      = false;
    }
}
