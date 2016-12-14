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

use Valkyrja\Contracts\View\View;
use Valkyrja\Support\Directory;
use Valkyrja\Support\ServiceProvider;
use Valkyrja\View\TwigView;

use Twig_Environment;
use Twig_Loader_Filesystem;

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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish() : void
    {
        // Check if twig is enabled in env
        if ($this->app->isTwigEnabled()) {
            // Set the env variable for views directory if its not set
            $this->app->config()->views->twig->dir = $this->app->config()->views->twig->dir ?? Directory::resourcesPath('views/twig');

            /**
             * Set Twig_Environment instance within container.
             */
            $this->app->container()->singleton(
                Twig_Environment::class,
                function () {
                    $loader = new Twig_Loader_Filesystem($this->app->config()->views->twig->dir);

                    $twig = new Twig_Environment(
                        $loader,
                        [
                            'cache'   => $this->app->config()->views->twig->compiledDir,
                            'debug'   => $this->app->config()->app->debug,
                            'charset' => 'utf-8',
                        ]
                    );

                    $extensions = $this->app->config()->views->twig->extensions ?? [];

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
            $this->app->container()->bind(
                View::class,
                function ($template = '', array $variables = []) {
                    $view = new TwigView($this->app, $template, $variables);

                    $view->setTwig($this->app->container()->get(Twig_Environment::class));

                    return $view;
                }
            );
        }
    }
}
 