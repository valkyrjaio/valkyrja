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

use Valkyrja\Contracts\Application;
use Valkyrja\Support\Helpers;

/**
 * Class ViewsConfig
 *
 * @package Valkyrja\Config\Sub
 */
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
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * ViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $this->dir = Helpers::env('VIEWS_DIR')
                ?? $app->resourcesPath('views/php');

            $this->twig = new TwigViewsConfig($app);
        }
    }
}
