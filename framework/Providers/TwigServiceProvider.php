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

use Valkyrja\Support\ServiceProvider;

/**
 * Class TwigServiceProvider
 *
 * @package Valkyrja\Providers
 *
 * @author  Melech Mizrachi
 */
class TwigServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function publish()
    {
        // Check if twig is enabled in env
        if ($this->app->isTwigEnabled()) {
            // Set the env variable for views directory if its not set
            $this->app->setConfig(
                'views.dir',
                $this->app->config('views.twig.dir', $this->app->resourcesPath('views/twig'))
            );

            // Set the env variable for views compiled directory if its not set
            $this->app->setConfig(
                'views.dir.compiled',
                $this->app->config('views.twig.dir.compiled', $this->app->storagePath('views/twig'))
            );

            /**
             * Set Twig_Environment instance within container.
             */
            $this->app->instance(
                \Twig_Environment::class,
                function () {
                    $loader = new \Twig_Loader_Filesystem($this->app->config('views.dir'));

                    $twig = new \Twig_Environment(
                        $loader, [
                                   'cache'   => $this->app->config('views.dir.compiled'),
                                   'debug'   => $this->app->debug(),
                                   'charset' => 'utf-8',
                               ]
                    );

                    $extensions = $this->app->config('views.twig.extensions');

                    // Twig Extensions registration
                    if (is_array($extensions)) {
                        foreach ($extensions as $extension) {
                            $twig->addExtension(new $extension());
                        }
                    }

                    return $twig;
                }
            );

            /**
             * Reset View instance within container for TwigView.
             */
            $this->app->instance(
                \Valkyrja\Contracts\View\View::class,
                [
                    function ($template = '', array $variables = []) {
                        $view = new \Valkyrja\View\TwigView($template, $variables);

                        $view->setTwig($this->app->container(\Twig_Environment::class));

                        return $view;
                    },
                ]
            );
        }
    }
}
 