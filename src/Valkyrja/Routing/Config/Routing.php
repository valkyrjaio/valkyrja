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
    protected function setup(array $properties = null): void
    {
        $this->middleware       = [];
        $this->middlewareGroups = [];
        $this->controllers      = [];
        $this->useTrailingSlash = false;
        $this->useAbsoluteUrls  = false;

        $this->filePath       = routesPath('default.php');
        $this->cacheFilePath  = cachePath('routes.php');
        $this->useAnnotations = false;
        $this->useCache       = false;
    }
}
