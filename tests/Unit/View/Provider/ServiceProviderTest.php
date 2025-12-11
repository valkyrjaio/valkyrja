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

namespace Valkyrja\Tests\Unit\View\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface as TwigExtensionInterface;
use Valkyrja\Application\Env;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;
use Valkyrja\View\Contract\Renderer as Contract;
use Valkyrja\View\Factory\Contract\ResponseFactory as ResponseFactoryContract;
use Valkyrja\View\Factory\ResponseFactory;
use Valkyrja\View\OrkaRenderer;
use Valkyrja\View\PhpRenderer;
use Valkyrja\View\Provider\ServiceProvider;
use Valkyrja\View\TwigRenderer;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishRenderer(): void
    {
        $this->container->setSingleton(PhpRenderer::class, $this->createStub(PhpRenderer::class));

        ServiceProvider::publishRenderer($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishPhpRenderer(): void
    {
        ServiceProvider::publishPhpRenderer($this->container);

        self::assertInstanceOf(PhpRenderer::class, $this->container->getSingleton(PhpRenderer::class));
    }

    public function testPublishOrkaRenderer(): void
    {
        ServiceProvider::publishOrkaRenderer($this->container);

        self::assertInstanceOf(OrkaRenderer::class, $this->container->getSingleton(OrkaRenderer::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishTwigRenderer(): void
    {
        $this->container->setSingleton(Environment::class, $this->createStub(Environment::class));

        ServiceProvider::publishTwigRenderer($this->container);

        self::assertInstanceOf(TwigRenderer::class, $this->container->getSingleton(TwigRenderer::class));
    }

    /**
     * @throws LoaderError
     */
    public function testPublishTwigEnvironment(): void
    {
        $this->container->setSingleton(
            Env::class,
            new class extends Env {
                /** @var array<string, string> */
                public const array VIEW_TWIG_PATHS = [
                    'namespace' => EnvClass::APP_DIR . '/storage',
                ];
                /** @var class-string<TwigExtensionInterface>[] */
                public const array VIEW_TWIG_EXTENSIONS = [
                    DebugExtension::class,
                ];
            }
        );

        ServiceProvider::publishTwigEnvironment($this->container);

        self::assertInstanceOf(Environment::class, $this->container->getSingleton(Environment::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishResponseFactory(): void
    {
        $this->container->setSingleton(HttpMessageResponseFactory::class, $this->createStub(HttpMessageResponseFactory::class));
        $this->container->setSingleton(Contract::class, $this->createStub(Contract::class));

        ServiceProvider::publishResponseFactory($this->container);

        self::assertInstanceOf(ResponseFactory::class, $this->container->getSingleton(ResponseFactoryContract::class));
    }
}
