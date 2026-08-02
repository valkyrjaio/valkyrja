<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\View\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Constant\TestPath;
use Valkyrja\Tests\Fixtures\View\Data\ViewConfigFixture;
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
use Valkyrja\View\Orka\Constant\OrkaReplacement;
use Valkyrja\View\Provider\ViewServiceProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ViewServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ViewConfigContract::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(ViewPhpConfigContract::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(ViewOrkaConfigContract::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(ViewTwigConfigContract::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(RendererContract::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(PhpRenderer::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(OrkaRenderer::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(TwigRenderer::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(Environment::class, new ViewServiceProvider()->publishers());
        self::assertArrayHasKey(ViewResponseFactoryContract::class, new ViewServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishConfig(): void
    {
        $callback = new ViewServiceProvider()->publishers()[ViewConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewConfigContract::class, $config = $this->container->getSingleton(ViewConfigContract::class));
        self::assertSame(PhpRenderer::class, $config->defaultRenderer);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new ViewConfigFixture());

        $callback = new ViewServiceProvider()->publishers()[ViewConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewConfigContract::class, $config = $this->container->getSingleton(ViewConfigContract::class));
        self::assertSame(OrkaRenderer::class, $config->defaultRenderer);
    }

    public function testPublishPhpConfig(): void
    {
        $callback = new ViewServiceProvider()->publishers()[ViewPhpConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewPhpConfigContract::class, $config = $this->container->getSingleton(ViewPhpConfigContract::class));
        self::assertSame('/resources/views', $config->phpPath);
    }

    public function testPublishPhpConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new ViewConfigFixture());

        $callback = new ViewServiceProvider()->publishers()[ViewPhpConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewPhpConfigContract::class, $config = $this->container->getSingleton(ViewPhpConfigContract::class));
        self::assertSame('/storage', $config->phpPath);
    }

    public function testPublishOrkaConfig(): void
    {
        $callback = new ViewServiceProvider()->publishers()[ViewOrkaConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewOrkaConfigContract::class, $config = $this->container->getSingleton(ViewOrkaConfigContract::class));
        self::assertSame('/resources/views', $config->orkaPath);
    }

    public function testPublishOrkaConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new ViewConfigFixture());

        $callback = new ViewServiceProvider()->publishers()[ViewOrkaConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewOrkaConfigContract::class, $config = $this->container->getSingleton(ViewOrkaConfigContract::class));
        self::assertSame('/storage', $config->orkaPath);
    }

    public function testPublishTwigConfig(): void
    {
        $callback = new ViewServiceProvider()->publishers()[ViewTwigConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewTwigConfigContract::class, $config = $this->container->getSingleton(ViewTwigConfigContract::class));
        self::assertSame('/storage/views', $config->twigCompiledPath);
    }

    public function testPublishTwigConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new ViewConfigFixture());

        $callback = new ViewServiceProvider()->publishers()[ViewTwigConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewTwigConfigContract::class, $config = $this->container->getSingleton(ViewTwigConfigContract::class));
        self::assertSame('/storage', $config->twigCompiledPath);
    }

    /**
     * @throws Exception
     */
    public function testPublishRenderer(): void
    {
        $this->container->setSingleton(ViewConfigContract::class, new ViewConfig());
        $this->container->setSingleton(PhpRenderer::class, self::createStub(PhpRenderer::class));

        $callback = new ViewServiceProvider()->publishers()[RendererContract::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(RendererContract::class));
    }

    public function testPublishPhpRenderer(): void
    {
        $this->container->setSingleton(ViewPhpConfigContract::class, new ViewPhpConfig(phpPath: '/storage'));

        $callback = new ViewServiceProvider()->publishers()[PhpRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(PhpRenderer::class));
    }

    public function testPublishOrkaRenderer(): void
    {
        $this->container->setSingleton(ViewOrkaConfigContract::class, new ViewOrkaConfig(orkaPath: '/storage'));

        $callback = new ViewServiceProvider()->publishers()[OrkaRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(OrkaRenderer::class, $this->container->getSingleton(OrkaRenderer::class));
    }

    public function testPublishOrkaRendererWithCustomReplacements(): void
    {
        $this->container->setSingleton(
            ViewOrkaConfigContract::class,
            new ViewOrkaConfig(
                orkaPath: '/storage',
                orkaCoreReplacements: [OrkaReplacement::LAYOUT],
                orkaReplacements: [OrkaReplacement::DEBUG],
            )
        );

        $callback = new ViewServiceProvider()->publishers()[OrkaRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(OrkaRenderer::class, $this->container->getSingleton(OrkaRenderer::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTwigRenderer(): void
    {
        $this->container->setSingleton(Environment::class, self::createStub(Environment::class));

        $callback = new ViewServiceProvider()->publishers()[TwigRenderer::class];
        $callback($this->container);

        self::assertInstanceOf(TwigRenderer::class, $this->container->getSingleton(TwigRenderer::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironment(): void
    {
        $this->container->setSingleton(
            ViewTwigConfigContract::class,
            new ViewTwigConfig(twigCompiledPath: '/storage')
        );

        $callback = new ViewServiceProvider()->publishers()[Environment::class];
        $callback($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironmentWithCustomEnv(): void
    {
        $this->container->setSingleton(
            ViewTwigConfigContract::class,
            new ViewTwigConfig(
                twigPaths: ['namespace' => TestPath::APP_DIR . '/storage'],
                twigExtensions: [DebugExtension::class],
                twigCompiledPath: '/storage',
            )
        );

        $callback = new ViewServiceProvider()->publishers()[Environment::class];
        $callback($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishResponseFactory(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));
        $this->container->setSingleton(RendererContract::class, self::createStub(RendererContract::class));

        $callback = new ViewServiceProvider()->publishers()[ViewResponseFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(ViewResponseFactory::class, $this->container->getSingleton(ViewResponseFactoryContract::class));
    }
}
