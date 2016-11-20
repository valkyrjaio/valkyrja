<?php

namespace config\config;

use config\Configs;

use Valkyrja\Application;

class ViewsConfig
{
    /**
     * Templates directory.
     *
     * @var string
     */
    public $dir;

    /**
     * Twig views config.
     *
     * @var TwigViewsConfig
     */
    public $twig;

    /**
     * ViewsConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->dir = Configs::env('VIEWS_DIR') ?? $app->resourcesPath('views/php');

        $this->twig = new TwigViewsConfig($app);
    }
}
