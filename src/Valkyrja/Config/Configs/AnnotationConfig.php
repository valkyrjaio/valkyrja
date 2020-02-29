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

use Valkyrja\Annotation\Enums\Config;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class AnnotationConfig.
 *
 * @author Melech Mizrachi
 */
class AnnotationConfig extends Model
{
    public bool   $enabled  = false;
    public string $cacheDir = '';
    public object $map;
    public object $aliases;

    /**
     * AnnotationConfig constructor.
     */
    public function __construct()
    {
        $this->enabled  = (bool) env(EnvKey::ANNOTATIONS_ENABLED, $this->enabled);
        $this->cacheDir = (string) env(EnvKey::ANNOTATIONS_CACHE_DIR, storagePath('vendor/annotations'));
        $this->setMap();
        $this->setAliases();
    }

    /**
     * Set the map.
     *
     * @param array $map
     *
     * @return void
     */
    protected function setMap(array $map = []): void
    {
        $this->map = (object) env(EnvKey::ANNOTATIONS_MAP, array_merge(Config::MAP, $map));
    }

    /**
     * Set the aliases.
     *
     * @param array $aliases
     *
     * @return void
     */
    protected function setAliases(array $aliases = []): void
    {
        $this->aliases = (object) env(EnvKey::ANNOTATIONS_ALIASES, array_merge(Config::ALIASES, $aliases));
    }
}
