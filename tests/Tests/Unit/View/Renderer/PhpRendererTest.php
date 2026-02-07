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

namespace Valkyrja\Tests\Unit\View\Renderer;

use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\RuntimeException;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Template\Template;
use Valkyrja\View\Throwable\Exception\InvalidConfigPath;

use const DIRECTORY_SEPARATOR;

/**
 * Test the PhpRenderer class.
 */
class PhpRendererTest extends TestCase
{
    protected const string TEMPLATES_DIR = EnvClass::APP_DIR . '/templates/php';

    public function testImplementsContract(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);

        self::assertInstanceOf(RendererContract::class, $renderer);
    }

    public function testStartAndEndRender(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);

        $renderer->startRender();
        echo 'Test content';
        $result = $renderer->endRender();

        self::assertSame('Test content', $result);
    }

    public function testEndRenderThrowsExceptionWhenObGetCleanFails(): void
    {
        $renderer = new class(self::TEMPLATES_DIR) extends PhpRenderer {
            protected function obGetClean(): string|false
            {
                return false;
            }
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Render failed');

        $renderer->endRender();
    }

    public function testCreateTemplate(): void
    {
        $templateName = 'test-page';
        $variables    = ['title' => 'Test Title'];

        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $template = $renderer->createTemplate($templateName, $variables);

        self::assertInstanceOf(Template::class, $template);
        self::assertSame($templateName, $template->getName());
        self::assertSame($variables, $template->getVariables());
    }

    public function testCreateTemplateWithoutVariables(): void
    {
        $templateName = 'test-page';

        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $template = $renderer->createTemplate($templateName);

        self::assertInstanceOf(Template::class, $template);
        self::assertSame($templateName, $template->getName());
        self::assertSame([], $template->getVariables());
    }

    public function testRenderFile(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $result   = $renderer->renderFile('home', ['title' => 'Home Page']);

        self::assertSame('<html lang="en"><body>Home Page</body></html>', $result);
    }

    public function testRenderFileWithCustomExtension(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR, '.php');
        $result   = $renderer->renderFile('page', ['content' => 'Hello World']);

        self::assertSame('<p>Hello World</p>', $result);
    }

    public function testRenderFileWithSubdirectory(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $result   = $renderer->renderFile('partials/header', ['siteName' => 'My Site']);

        self::assertSame('<header>My Site</header>', $result);
    }

    public function testRenderFileThrowsExceptionForMissingFile(): void
    {
        $renderer        = new PhpRenderer(self::TEMPLATES_DIR);
        $exceptionThrown = false;

        try {
            $renderer->renderFile('nonexistent');
        } catch (RuntimeException $e) {
            $exceptionThrown = true;
            self::assertStringContainsString('Path does not exist at', $e->getMessage());
        } finally {
            while (ob_get_level() > 1) {
                ob_end_clean();
            }
        }

        self::assertTrue($exceptionThrown, 'Expected RuntimeException was not thrown');
    }

    public function testRenderFileWithConfiguredPath(): void
    {
        $renderer = new PhpRenderer(
            self::TEMPLATES_DIR,
            '.phtml',
            ['@custom' => self::TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'custom']
        );

        $result = $renderer->renderFile('@custom/widget', ['name' => 'Widget Name']);

        self::assertSame('<div>Widget Name</div>', $result);
    }

    public function testRenderFileThrowsExceptionForInvalidConfigPath(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);

        $this->expectException(InvalidConfigPath::class);
        $this->expectExceptionMessage('Invalid path @unknown specified for template @unknown/test');

        $renderer->renderFile('@unknown/test');
    }

    public function testRender(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $result   = $renderer->render('simple');

        self::assertSame('Simple content', $result);
    }

    public function testRenderWithVariables(): void
    {
        $renderer = new PhpRenderer(self::TEMPLATES_DIR);
        $result   = $renderer->render('greeting', ['name' => 'World']);

        self::assertSame('Hello, World!', $result);
    }
}
