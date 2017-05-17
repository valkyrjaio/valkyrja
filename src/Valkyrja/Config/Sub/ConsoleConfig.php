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
 * Class ConsoleConfig.
 *
 * @author Melech Mizrachi
 */
class ConsoleConfig
{
    /**
     * Use annotations for commands?
     *
     * @var bool
     */
    public $useAnnotations = false;

    /**
     * Use only annotations without commands file?
     *
     * @var bool
     */
    public $useAnnotationsExclusively = false;

    /**
     * Command handlers to get annotations from.
     *
     * @var array
     */
    public $handlers = [];

    /**
     * The commands file path.
     *
     * @var string
     */
    public $filePath;

    /**
     * The commands cache file path.
     *
     * @var string
     */
    public $cacheFilePath;

    /**
     * Whether to use the commands cache file.
     *
     * @var bool
     */
    public $useCacheFile = false;

    /**
     * ConsoleConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->useAnnotations            = $env::CONSOLE_USE_ANNOTATIONS
            ?? $this->useAnnotations;
        $this->useAnnotationsExclusively = $env::CONSOLE_USE_ANNOTATIONS_EXCLUSIVELY
            ?? $this->useAnnotationsExclusively;
        $this->handlers                  = $env::CONSOLE_HANDLERS
            ?? $this->handlers;
        $this->filePath                  = $env::CONSOLE_FILE_PATH
            ?? Directory::basePath('bootstrap/commands.php');
        $this->cacheFilePath             = $env::CONSOLE_CACHE_FILE_PATH
            ?? Directory::storagePath('framework/cache/commands.php');
        $this->useCacheFile              = $env::CONSOLE_USE_CACHE_FILE
            ?? $this->useCacheFile;
    }
}
