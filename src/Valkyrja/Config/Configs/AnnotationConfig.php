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
    /**
     * Flag for whether annotations are enabled.
     *
     * @var bool
     */
    public bool $enabled;

    /**
     * The cache dir.
     *
     * @var string
     */
    public string $cacheDir;

    /**
     * The annotations map.
     *
     * @example
     * <code>
     *      [
     *         'Annotation' => Annotation::class,
     *      ]
     * </code>
     *
     * @var array
     */
    public array $map;

    /**
     * The annotation aliases.
     *
     * @example
     * <code>
     *      [
     *         'Word' => WordEnum::class,
     *      ]
     * </code>
     * Then we can do:
     * <code>
     * @Annotation("name" : "Word::VALUE")
     * </code>
     *
     * @var array
     */
    public array $aliases;

    /**
     * AnnotationConfig constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setEnabled();
        $this->setCacheDir(storagePath('framework/annotations'));
        $this->setMap();
        $this->setAliases();
    }

    /**
     * Set the enabled flag.
     *
     * @param bool $enabled
     *
     * @return void
     */
    protected function setEnabled(bool $enabled = false): void
    {
        $this->enabled = (bool) env(EnvKey::ANNOTATIONS_ENABLED, $enabled);
    }

    /**
     * Set the cache dir.
     *
     * @param string $cacheDir
     *
     * @return void
     */
    protected function setCacheDir(string $cacheDir = ''): void
    {
        $this->cacheDir = (string) env(EnvKey::ANNOTATIONS_CACHE_DIR, $cacheDir);
    }

    /**
     * Set the map.
     *
     * @param array $map
     *
     * @return void
     */
    protected function setMap(array $map = Config::MAP): void
    {
        $this->map = (array) env(EnvKey::ANNOTATIONS_MAP, $map);
    }

    /**
     * Set the aliases.
     *
     * @param array $aliases
     *
     * @return void
     */
    protected function setAliases(array $aliases = Config::ALIASES): void
    {
        $this->aliases = (array) env(EnvKey::ANNOTATIONS_ALIASES, $aliases);
    }
}
