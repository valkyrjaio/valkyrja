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

use Twig_Loader_Filesystem;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Support\Directory;

/**
 * Class TwigViewsConfig.
 *
 * @author Melech Mizrachi
 */
class TwigViewsConfig
{
    /**
     * Whether twig templating is enabled.
     *
     * @var bool
     */
    public $enabled = false;

    /**
     * Twig templates directory.
     *
     * @var string
     */
    public $dir;

    /**
     * Twig templates directories.
     *
     * @var array
     */
    public $dirs = [];

    /**
     * Twig compiled templates directory.
     *
     * @var string
     */
    public $compiledDir;

    /**
     * Twig extensions.
     *
     * @var array
     */
    public $extensions = [];

    /**
     * TwigViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->dir         = $env::VIEWS_TWIG_DIR ?? Directory::resourcesPath('views/twig');
        $this->dirs        = $env::VIEWS_TWIG_DIRS ?? $this->dirs;
        $this->compiledDir = $env::VIEWS_TWIG_COMPILED_DIR ?? Directory::storagePath('views/twig');
        $this->extensions  = $env::VIEWS_TWIG_EXTENSIONS ?? $this->extensions;

        // Add the main directory to the list of directories as the main namespace
        $this->dirs[Twig_Loader_Filesystem::MAIN_NAMESPACE] = $this->dir;
    }
}
