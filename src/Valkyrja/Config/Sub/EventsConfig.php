<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Config\Env;
use Valkyrja\Support\Directory;

/**
 * Class EventsConfig.
 *
 * @author Melech Mizrachi
 */
class EventsConfig
{
    /**
     * Use annotations for listeners?
     *
     * @var bool
     */
    public $useAnnotations = false;

    /**
     * Use only annotations without events file?
     *
     * @var bool
     */
    public $useAnnotationsExclusively = false;

    /**
     * Classes to get annotations from.
     *
     * @var array
     */
    public $classes = [];

    /**
     * The events file path.
     *
     * @var string
     */
    public $filePath;

    /**
     * The events cache file path.
     *
     * @var string
     */
    public $cacheFilePath;

    /**
     * Whether to use the events cache file.
     *
     * @var bool
     */
    public $useCacheFile = false;

    /**
     * EventsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->useAnnotations            = $env::EVENTS_USE_ANNOTATIONS
            ?? $this->useAnnotations;
        $this->useAnnotationsExclusively = $env::EVENTS_USE_ANNOTATIONS_EXCLUSIVELY
            ?? $this->useAnnotationsExclusively;
        $this->classes                   = $env::EVENTS_CLASSES
            ?? $this->classes;
        $this->filePath                  = $env::EVENTS_FILE_PATH
            ?? Directory::basePath('bootstrap/events.php');
        $this->cacheFilePath             = $env::EVENTS_CACHE_FILE_PATH
            ?? Directory::storagePath('framework/cache/events.php');
        $this->useCacheFile              = $env::EVENTS_USE_CACHE_FILE
            ?? $this->useCacheFile;
    }
}
