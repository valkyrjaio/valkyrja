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
 * Class ViewsConfig.
 *
 * @author Melech Mizrachi
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
     * Whether to use twig or not.
     *
     * @var bool
     */
    public $useTwig = false;

    /**
     * Twig views config.
     *
     * @var TwigViewsConfig
     */
    public $twig;

    /**
     * ViewsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->dir     = $env::VIEWS_DIR ?? Directory::resourcesPath('views/php');
        $this->useTwig = $env::VIEWS_USE_TWIG ?? $this->useTwig;

        if ($this->useTwig) {
            $this->twig = new TwigViewsConfig($env);
        }
    }
}
