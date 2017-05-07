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

use Valkyrja\Console\Command;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Container\Service;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Events\Listener;
use Valkyrja\Routing\Route;
use Valkyrja\Support\Directory;

/**
 * Class AnnotationsConfig
 *
 * @package Valkyrja\Config\Sub
 *
 * @author  Melech Mizrachi
 */
class AnnotationsConfig
{
    /**
     * Enable annotations?
     *
     * @var bool
     */
    public $enabled = false;

    /**
     * Cache directory to use for annotations.
     *
     * @var string
     */
    public $cacheDir;

    /**
     * Map of annotation types to the class.
     *
     * @var array
     */
    public $map = [
        'Command'        => Command::class,
        'Listener'       => Listener::class,
        'Route'          => Route::class,
        'Service'        => Service::class,
        'ServiceAlias'   => Service::class,
        'ServiceContext' => ServiceContext::class,
    ];

    /**
     * AnnotationsConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->enabled = $env::ANNOTATIONS_ENABLED ?? false;
        $this->cacheDir = $env::ANNOTATIONS_CACHE_DIR ?? Directory::storagePath('vendor/annotations');
        $this->map = array_merge($env::ANNOTATIONS_MAP ?? [], $this->map);
    }
}
