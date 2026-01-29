<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\View\Provider;

use Override;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;
use Valkyrja\View\Factory\ResponseFactory;
use Valkyrja\View\Orka\Constant\OrkaReplacement;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            RendererContract::class        => [self::class, 'publishRenderer'],
            PhpRenderer::class             => [self::class, 'publishPhpRenderer'],
            OrkaRenderer::class            => [self::class, 'publishOrkaRenderer'],
            TwigRenderer::class            => [self::class, 'publishTwigRenderer'],
            Environment::class             => [self::class, 'publishTwigEnvironment'],
            ResponseFactoryContract::class => [self::class, 'publishResponseFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RendererContract::class,
            PhpRenderer::class,
            OrkaRenderer::class,
            TwigRenderer::class,
            Environment::class,
            ResponseFactoryContract::class,
        ];
    }

    /**
     * Publish the renderer service.
     */
    public static function publishRenderer(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<RendererContract> $default */
        $default = $env::VIEW_DEFAULT_RENDERER ?? PhpRenderer::class;

        $container->setSingleton(
            RendererContract::class,
            $container->getSingleton($default)
        );
    }

    /**
     * Publish the renderer service.
     */
    public static function publishPhpRenderer(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $dir */
        $dir = $env::VIEW_PHP_PATH
            ?? '/resources/views';
        /** @var non-empty-string $fileExtension */
        $fileExtension = $env::VIEW_PHP_FILE_EXTENSION
            ?? '.phtml';
        /** @var array<string, string> $paths */
        $paths = $env::VIEW_PHP_PATHS
            ?? [];

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
        $env = $container->getSingleton(Env::class);
        /** @var bool $debug */
        $debug = $env::APP_DEBUG_MODE;
        /** @var non-empty-string $dir */
        $dir = $env::VIEW_ORKA_PATH
            ?? '/resources/views';
        /** @var non-empty-string $fileExtension */
        $fileExtension = $env::VIEW_ORKA_FILE_EXTENSION
            ?? '.orka.phtml';
        /** @var array<non-empty-string, non-empty-string> $paths */
        $paths = $env::VIEW_ORKA_PATHS
            ?? [];
        /** @var class-string<ReplacementContract>[] $coreReplacements */
        $coreReplacements = $env::VIEW_ORKA_CORE_REPLACEMENTS
            ?? [
                OrkaReplacement::LAYOUT,
                OrkaReplacement::BLOCK,
                OrkaReplacement::END_BLOCK,
                OrkaReplacement::START_BLOCK,
                OrkaReplacement::TRIM_BLOCK,
                OrkaReplacement::END_MULTILINE_COMMENT,
                OrkaReplacement::SINGLE_LINE_COMMENT,
                OrkaReplacement::START_MULTILINE_COMMENT,
                OrkaReplacement::PARTIAL,
                OrkaReplacement::PARTIAL_WITH_VARIABLES,
                OrkaReplacement::TRIM_PARTIAL,
                OrkaReplacement::TRIM_PARTIAL_WITH_VARIABLES,
                OrkaReplacement::BREAK_,
                OrkaReplacement::ELSE_HAS_BLOCK,
                OrkaReplacement::HAS_BLOCK,
                OrkaReplacement::UNLESS_BLOCK,
                OrkaReplacement::ELSE_,
                OrkaReplacement::ELSE_IF,
                OrkaReplacement::ELSE_UNLESS,
                OrkaReplacement::EMPTY_,
                OrkaReplacement::END_IF,
                OrkaReplacement::IF_,
                OrkaReplacement::ISSET_,
                OrkaReplacement::NOT_EMPTY,
                OrkaReplacement::UNLESS,
                OrkaReplacement::END_FOR,
                OrkaReplacement::END_FOREACH,
                OrkaReplacement::FOR_,
                OrkaReplacement::FOREACH_,
                OrkaReplacement::CASE_,
                OrkaReplacement::DEFAULT_,
                OrkaReplacement::END_SWITCH,
                OrkaReplacement::SWITCH_,
                OrkaReplacement::ESCAPED,
                OrkaReplacement::SET_VARIABLE,
                OrkaReplacement::SET_VARIABLES,
                OrkaReplacement::UNESCAPED,
            ];
        /** @var class-string<ReplacementContract>[] $replacements */
        $replacements = $env::VIEW_ORKA_REPLACEMENTS
            ?? [
                OrkaReplacement::DEBUG,
            ];

        $allReplacements = array_merge($coreReplacements, $replacements);

        $replacementClasses = [];

        foreach ($allReplacements as $replacement) {
            $replacementClasses[] = $container->get($replacement);
        }

        $container->setSingleton(
            OrkaRenderer::class,
            new OrkaRenderer(
                Directory::basePath(path: $dir),
                $fileExtension,
                $paths,
                Directory::storagePath('views/'),
                $debug,
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
        $env = $container->getSingleton(Env::class);
        /** @var bool $debug */
        $debug = $env::APP_DEBUG_MODE;
        /** @var array<string, string> $paths */
        $paths = $env::VIEW_TWIG_PATHS
            ?? [];
        /** @var class-string<ExtensionInterface>[] $extensions */
        $extensions = $env::VIEW_TWIG_EXTENSIONS
            ?? [];
        /** @var non-empty-string $compiledDir */
        $compiledDir = $env::VIEW_TWIG_COMPILED_PATH
            ?? '/storage/views';

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
                'debug'   => $debug,
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
            ResponseFactoryContract::class,
            new ResponseFactory(
                $container->getSingleton(HttpMessageResponseFactory::class),
                $container->getSingleton(RendererContract::class)
            )
        );
    }
}
