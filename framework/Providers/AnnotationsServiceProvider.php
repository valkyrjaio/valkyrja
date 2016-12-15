<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Providers;

use Valkyrja\Routing\Annotations\Route;
use Valkyrja\Support\ServiceProvider;

use mindplay\annotations\AnnotationCache;
use mindplay\annotations\Annotations;

/**
 * Class AnnotationsServiceProvider
 *
 * @package Valkyrja\Providers
 *
 * @author  Melech Mizrachi
 */
class AnnotationsServiceProvider extends ServiceProvider
{
    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish() : void
    {
        if (! $this->app->config()->annotations->enabled) {
            return;
        }

        Annotations::$config['cache'] = new AnnotationCache($this->app->config()->annotations->cacheDir);

        $annotationManager = Annotations::getManager();

        $annotationManager->registry['route'] = Route::class;
    }
}
 