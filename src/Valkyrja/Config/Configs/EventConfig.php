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
use Valkyrja\Config\Models\CacheableConfig as Model;

/**
 * Class EventConfig.
 *
 * @author Melech Mizrachi
 */
class EventConfig extends Model
{
    /**
     * The annotated listeners.
     *
     * @var array
     */
    public array $listeners = [];

    /**
     * EventConfig constructor.
     */
    public function __construct()
    {
        $this->setListeners();

        $this->setFilePathEnvKey(EnvKey::EVENT_FILE_PATH);
        $this->setCacheFilePathEnvKey(EnvKey::EVENT_CACHE_FILE_PATH);
        $this->setUseCacheEnvKey(EnvKey::EVENT_USE_CACHE_FILE);
        $this->setUseAnnotationsEnvKey(EnvKey::EVENT_USE_ANNOTATIONS);
        $this->setUseAnnotationsExclusivelyEnvKey(EnvKey::EVENT_USE_ANNOTATIONS_EXCLUSIVELY);

        $this->setFilePath(eventsPath('default.php'));
        $this->setCacheFilePath(cachePath('events.php'));
        $this->setUseCache();
        $this->setAnnotationsConfig();
    }

    /**
     * Set the annotated listeners.
     *
     * @param array $listeners [optional] The annotated listeners
     *
     * @return void
     */
    protected function setListeners(array $listeners = []): void
    {
        $this->listeners = (array) env(EnvKey::EVENT_LISTENERS, $listeners ?? $this->listeners);
    }
}