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
    public array $listeners = [];

    /**
     * EventConfig constructor.
     */
    public function __construct()
    {
        $this->listeners = (array) env(EnvKey::EVENT_LISTENERS, $this->listeners);

        $this->envUseAnnotationsKey            = EnvKey::EVENT_USE_ANNOTATIONS;
        $this->envUseAnnotationsExclusivelyKey = EnvKey::EVENT_USE_ANNOTATIONS_EXCLUSIVELY;
        $this->setAnnotationsConfig();

        $this->filePath            = eventsPath('default.php');
        $this->cacheFilePath       = cachePath('events.php');
        $this->envFilePathKey      = EnvKey::EVENT_FILE_PATH;
        $this->envCacheFilePathKey = EnvKey::EVENT_CACHE_FILE_PATH;
        $this->envUseCacheKey      = EnvKey::EVENT_USE_CACHE_FILE;
        $this->setCacheableConfig();
    }
}
