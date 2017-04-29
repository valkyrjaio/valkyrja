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

use Valkyrja\Container\Service;
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
     * Whether the service provider is deferred.
     *
     * @var bool
     */
    public static $deferred = false;

    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        Twig_Environment::class,
        View::class,
    ];

    /**
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        if (! $this->app->isTwigEnabled()) {
            return;
        }

        // Set the env variable for views directory if its not set
        $this->app->config()->views->twig->dir = $this->app->config()->views->twig->dir
            ?? Directory::resourcesPath('views/twig');

        $this->app->container()->bind(
            (new Service())
                ->setId(Twig_Environment::class)
                ->setSingleton(true)
                ->setClass(static::class)
                ->setMethod('getTwigEnvironment')
                ->setStatic(true)
        );

        $this->app->container()->bind(
            (new Service())
                ->setId(View::class)
                ->setClass(static::class)
                ->setMethod('getTwigView')
                ->setStatic(true)
        );
    }

    /**
     * Get the twig environment.
     *
     * @return \Twig_Environment
     */
    public static function getTwigEnvironment(): Twig_Environment
    {
        // TODO: Ability to do loader for third party vendors
        // config()->views->twig->map [ 'name' => 'directory' ]
        $loader = new Twig_Loader_Filesystem(config()->views->twig->dir);

        $twig = new Twig_Environment(
            $loader,
            [
                'cache'   => config()->views->twig->compiledDir,
                'debug'   => config()->app->debug,
                'charset' => 'utf-8',
            ]
        );

        $extensions = config()->views->twig->extensions ?? [];

        // Twig Extensions registration
        if (is_array($extensions)) {
            foreach ($extensions as $extension) {
                $twig->addExtension(new $extension());
            }
        }

        return $twig;
    }

    /**
     * Get the twig view when building a service container item.
     *
     * @param string $template The template
     * @param array  $variables The variables
     *
     * @return \Valkyrja\View\TwigView
     */
    public static function getTwigView($template = '', array $variables = []): TwigView
    {
        $view = new TwigView(app(), $template, $variables);

        $view->setTwig(container()->get(Twig_Environment::class));

        return $view;
    }
}
 