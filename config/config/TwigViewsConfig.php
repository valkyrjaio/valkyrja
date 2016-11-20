<?php

namespace config\config;

use config\Configs;

use Valkyrja\Application;

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
     * TwigViewsConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->enabled = Configs::env('VIEWS_TWIG_ENABLED') ?? false;
        $this->dir = Configs::env('VIEWS_TWIG_DIR') ?? $app->resourcesPath('views/twig');
        $this->compiledDir = Configs::env('VIEWS_TWIG_COMPILED_DIR') ?? $app->storagePath('views/twig');
        $this->extensions = Configs::env('VIEWS_TWIG_EXTENSIONS') ?? [];
    }
}
