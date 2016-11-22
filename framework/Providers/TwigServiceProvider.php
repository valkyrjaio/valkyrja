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

use Twig_Environment;
use Twig_Loader_Filesystem;

use Valkyrja\Contracts\View\View;
use Valkyrja\Support\Helpers;
use Valkyrja\Support\ServiceProvider;
use Valkyrja\View\TwigView;

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
    public function publish() // : void
    {
        // Check if twig is enabled in env
        if ($this->app->isTwigEnabled()) {
            // Set the env variable for views directory if its not set
            Helpers::config()->views->twig->dir = Helpers::config()->views->twig->dir
                ?? $this->app->resourcesPath('views/twig');

            /**
             * Set Twig_Environment instance within container.
             */
            Helpers::container()->instance(
                Twig_Environment::class,
                function () {
                    $loader = new Twig_Loader_Filesystem(Helpers::config()->views->twig->dir);

                    $twig = new Twig_Environment(
                        $loader,
                        [
                            'cache'   => Helpers::config()->views->twig->compiledDir,
                            'debug'   => Helpers::config()->app->debug,
                            'charset' => 'utf-8',
                        ]
                    );

                    $extensions = Helpers::config()->views->twig->extensions ?? [];

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
            Helpers::container()->instance(
                View::class,
                [
                    function ($template = '', array $variables = []) {
                        $view = new TwigView($template, $variables);

                        $view->setTwig(Helpers::container()->get(Twig_Environment::class));

                        return $view;
                    },
                ]
            );
        }
    }
}
 