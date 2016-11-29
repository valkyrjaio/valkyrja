<?php

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Application;
use Valkyrja\Support\Helpers;

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
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $this->enabled = Helpers::env('VIEWS_TWIG_ENABLED') ?? false;
            $this->dir = Helpers::env('VIEWS_TWIG_DIR') ?? $app->resourcesPath('views/twig');
            $this->compiledDir = Helpers::env('VIEWS_TWIG_COMPILED_DIR') ?? $app->storagePath('views/twig');
            $this->extensions = Helpers::env('VIEWS_TWIG_EXTENSIONS') ?? [];
        }
    }
}
