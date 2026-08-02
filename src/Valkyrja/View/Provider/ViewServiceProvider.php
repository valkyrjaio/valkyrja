<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Provider;

use Override;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Data\Contract\ViewConfigContract;
use Valkyrja\View\Data\Contract\ViewOrkaConfigContract;
use Valkyrja\View\Data\Contract\ViewPhpConfigContract;
use Valkyrja\View\Data\Contract\ViewTwigConfigContract;
use Valkyrja\View\Data\ViewConfig;
use Valkyrja\View\Data\ViewOrkaConfig;
use Valkyrja\View\Data\ViewPhpConfig;
use Valkyrja\View\Data\ViewTwigConfig;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;
use Valkyrja\View\Factory\ViewResponseFactory;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

use function array_merge;

class ViewServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the view config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof ViewConfigContract) {
            $container->setSingleton(ViewConfigContract::class, $config);

            return;
        }

        $container->setSingleton(ViewConfigContract::class, new ViewConfig());
    }

    /**
     * Publish the php renderer config service.
     */
    public static function publishPhpConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof ViewPhpConfigContract) {
            $container->setSingleton(ViewPhpConfigContract::class, $config);

            return;
        }

        $container->setSingleton(ViewPhpConfigContract::class, new ViewPhpConfig());
    }

    /**
     * Publish the orka renderer config service.
     */
    public static function publishOrkaConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof ViewOrkaConfigContract) {
            $container->setSingleton(ViewOrkaConfigContract::class, $config);

            return;
        }

        $container->setSingleton(ViewOrkaConfigContract::class, new ViewOrkaConfig());
    }

    /**
     * Publish the twig renderer config service.
     */
    public static function publishTwigConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof ViewTwigConfigContract) {
            $container->setSingleton(ViewTwigConfigContract::class, $config);

            return;
        }

        $container->setSingleton(ViewTwigConfigContract::class, new ViewTwigConfig());
    }

    /**
     * Publish the renderer service.
     */
    public static function publishRenderer(ContainerContract $container): void
    {
        $config = $container->getSingleton(ViewConfigContract::class);

        $container->setSingleton(
            RendererContract::class,
            $container->getSingleton($config->defaultRenderer)
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishPhpRenderer(ContainerContract $container): void
    {
        $config = $container->getSingleton(ViewPhpConfigContract::class);

        $dir           = $config->phpPath;
        $fileExtension = $config->phpFileExtension;
        $paths         = $config->phpPaths;

        $container->setSingleton(
            PhpRenderer::class,
            new PhpRenderer(
                dir: Directory::basePath(path: $dir),
                fileExtension: $fileExtension,
                paths: $paths
            ),
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishOrkaRenderer(ContainerContract $container): void
    {
        $app    = $container->getSingleton(ApplicationContract::class);
        $config = $container->getSingleton(ViewOrkaConfigContract::class);

        $dir           = $config->orkaPath;
        $fileExtension = $config->orkaFileExtension;
        $paths         = $config->orkaPaths;

        $replacementClasses = self::getOrkaComponents($container, $config);

        $container->setSingleton(
            OrkaRenderer::class,
            new OrkaRenderer(
                Directory::basePath(path: $dir),
                $fileExtension,
                $paths,
                Directory::storagePath('views/'),
                $app->getDebugMode(),
                ...$replacementClasses,
            ),
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishTwigRenderer(ContainerContract $container): void
    {
        $container->setSingleton(
            TwigRenderer::class,
            new TwigRenderer(
                $container->getSingleton(Environment::class),
            ),
        );
    }

    /**
     * Publish the renderer service.
     *
     * @throws LoaderError
     */
    public static function publishTwigEnvironment(ContainerContract $container): void
    {
        $app    = $container->getSingleton(ApplicationContract::class);
        $config = $container->getSingleton(ViewTwigConfigContract::class);

        $paths       = $config->twigPaths;
        $extensions  = $config->twigExtensions;
        $compiledDir = $config->twigCompiledPath;

        // Get the twig filesystem loader
        $loader = new FilesystemLoader();

        // Iterate through the dirs and add each as a path in the twig loader
        foreach ($paths as $namespace => $dir) {
            $loader->addPath($dir, $namespace);
        }

        // Create a new twig environment
        $twig = new Environment(
            $loader,
            [
                'cache'   => Directory::basePath(path: $compiledDir),
                'debug'   => $app->getDebugMode(),
                'charset' => 'utf-8',
            ]
        );

        // Iterate through the extensions
        foreach ($extensions as $extension) {
            // And add each extension to the twig environment
            $twig->addExtension(new $extension());
        }

        $container->setSingleton(
            Environment::class,
            $twig,
        );
    }

    /**
     * Publish the response factory service.
     */
    public static function publishResponseFactory(ContainerContract $container): void
    {
        $container->setSingleton(
            ViewResponseFactoryContract::class,
            new ViewResponseFactory(
                $container->getSingleton(ResponseFactoryContract::class),
                $container->getSingleton(RendererContract::class)
            )
        );
    }

    /**
     * Get the orka components.
     *
     * @return ReplacementContract[]
     */
    private static function getOrkaComponents(ContainerContract $container, ViewOrkaConfigContract $config): array
    {
        $allReplacements = array_merge($config->orkaCoreReplacements, $config->orkaReplacements);

        $replacementClasses = [];

        foreach ($allReplacements as $replacement) {
            $replacementClasses[] = $container->get($replacement);
        }

        return $replacementClasses;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ViewConfigContract::class          => [self::class, 'publishConfig'],
            ViewPhpConfigContract::class       => [self::class, 'publishPhpConfig'],
            ViewOrkaConfigContract::class      => [self::class, 'publishOrkaConfig'],
            ViewTwigConfigContract::class      => [self::class, 'publishTwigConfig'],
            RendererContract::class            => [self::class, 'publishRenderer'],
            PhpRenderer::class                 => [self::class, 'publishPhpRenderer'],
            OrkaRenderer::class                => [self::class, 'publishOrkaRenderer'],
            TwigRenderer::class                => [self::class, 'publishTwigRenderer'],
            Environment::class                 => [self::class, 'publishTwigEnvironment'],
            ViewResponseFactoryContract::class => [self::class, 'publishResponseFactory'],
        ];
    }
}
