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
    /**
     * The dir.
     *
     * @var string
     */
    public string $dir;

    /**
     * The default engine.
     *
     * @var string
     */
    public string $engine;

    /**
     * The engines.
     *
     * @var array
     */
    public array $engines;

    /**
     * The paths.
     *
     * @example
     * <code>
     *      [
     *         '@path' => '/some/path/on/disk',
     *      ]
     * </code>
     * Then we can do:
     * <code>
     *      view('@path/template');
     *      $view->layout('@path/layout');
     *      $view->partial('@path/partials/partial');
     * </code>
     *
     * @var array
     */
    public array $paths;

    /**
     * ViewConfig constructor.
     */
    public function __construct()
    {
        $this->setDir(resourcesPath('views'));
        $this->setEngine();
        $this->setEngines();
        $this->setPaths();
    }

    /**
     * Set the dir.
     *
     * @param string $dir [optional] The dir
     *
     * @return void
     */
    protected function setDir(string $dir = ''): void
    {
        $this->dir = (string) env(EnvKey::VIEW_DIR, $dir);
    }

    /**
     * Set the default engine.
     *
     * @param string $engine [optional] The default engine
     *
     * @return void
     */
    protected function setEngine(string $engine = Config::ENGINE): void
    {
        $this->engine = (string) env(EnvKey::VIEW_ENGINE, $engine);
    }

    /**
     * Set the engines.
     *
     * @param array $engines [optional] The engines
     *
     * @return void
     */
    protected function setEngines(array $engines = []): void
    {
        $this->engines = (array) env(EnvKey::VIEW_ENGINES, array_merge(Config::ENGINES, $engines));
    }

    /**
     * Set the paths.
     *
     * @param array $paths [optional] The paths
     *
     * @return void
     */
    protected function setPaths(array $paths = []): void
    {
        $this->paths = (array) env(EnvKey::VIEW_PATHS, $paths);
    }
}