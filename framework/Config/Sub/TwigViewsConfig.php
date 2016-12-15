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
 * Class TwigViewsConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author  Melech Mizrachi
 */
class TwigViewsConfig
{
    /**
     * Whether twig templating is enabled.
     *
     * @var bool
     */
    public $enabled;

    /**
     * Twig templates directory.
     *
     * @var string
     */
    public $dir;

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
    public $extensions;

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * TwigViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        if ($this->setDefaults) {
            $this->enabled = $env::VIEWS_TWIG_ENABLED ?? false;
            $this->dir = $env::VIEWS_TWIG_DIR ?? Directory::resourcesPath('views/twig');
            $this->compiledDir = $env::VIEWS_TWIG_COMPILED_DIR ?? Directory::storagePath('views/twig');
            $this->extensions = $env::VIEWS_TWIG_EXTENSIONS ?? [];
        }
    }
}
