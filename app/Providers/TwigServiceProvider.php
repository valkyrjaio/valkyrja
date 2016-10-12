<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Providers;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Valkyrja\Contracts\View\View as ViewContract;
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
     * @inheritdoc
     */
    public function publish()
    {
        // Set the Twig_Environment class in the service container
        $this->app->instance(
            Twig_Environment::class,
            function () {
                $loader = new Twig_Loader_Filesystem($this->app->config('views.dir'));

                $twig = new Twig_Environment(
                    $loader, [
                               'cache' => $this->app->config('views.dir.compiled'),
                           ]
                );

                // Add Twig Extensions Here
                // $twig->addExtension(new \App\Views\Extensions\TwigStaticExtension());

                return $twig;
            }
        );

        // Set the View class in the service container as Twig view
        $this->app->instance(
            ViewContract::class,
            [
                function ($template = '', array $variables = []) {
                    $view = new TwigView($template, $variables);

                    $view->setTwig($this->app->container(Twig_Environment::class));

                    return $view;
                },
            ]
        );
    }
}
 