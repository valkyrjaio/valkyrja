<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;
use Valkyrja\View\Enums\Config;

/**
 * Class ViewConfig.
 *
 * @author Melech Mizrachi
 */
class ViewConfig extends Model
{
    public string $dir     = '';
    public string $engine  = Config::ENGINE;
    public array  $engines = [];
    public array  $paths   = [];

    /**
     * ViewConfig constructor.
     */
    public function __construct()
    {
        $this->dir     = (string) env(EnvKey::VIEW_DIR, resourcesPath('views'));
        $this->engine  = (string) env(EnvKey::VIEW_ENGINE, $this->engine);
        $this->engines = (array) env(EnvKey::VIEW_ENGINES, array_merge(Config::ENGINES, $this->engines));
        $this->paths   = (array) env(EnvKey::VIEW_PATHS, $this->paths);
    }
}
