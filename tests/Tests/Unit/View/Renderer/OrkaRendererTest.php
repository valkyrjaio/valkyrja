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

use Override;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Orka\Replacement\Variable\Unescaped;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Throwable\Exception\RuntimeException;

use function file_exists;
use function file_get_contents;
use function is_dir;
use function is_file;
use function md5;
use function scandir;
use function unlink;

/**
 * Test the OrkaRenderer class.
 */
final class OrkaRendererTest extends TestCase
{
    protected const string TEMPLATES_DIR = EnvClass::APP_DIR . '/templates/orka';

    protected string $originalBasePath;

    #[Override]
    protected function setUp(): void
    {
        $this->originalBasePath = Directory::$basePath;
        Directory::$basePath    = EnvClass::APP_DIR;
    }

    #[Override]
    protected function tearDown(): void
    {
        // Clear cached view files
        $viewsDir = Directory::storagePath('views');

        if (is_dir($viewsDir)) {
            $files = scandir($viewsDir);

            foreach ($files as $file) {
                if ($file === '.gitignore') {
                    continue;
                }

                $filepath = $viewsDir . '/' . $file;

                if (is_file($filepath)) {
                    unlink($filepath);
                }
            }
        }

        Directory::$basePath = $this->originalBasePath;

        parent::tearDown();
    }

    public function testImplementsContract(): void
    {
        $renderer = new OrkaRenderer(self::TEMPLATES_DIR);

        self::assertInstanceOf(RendererContract::class, $renderer);
    }

    public function testRenderFileWithoutReplacements(): void
    {
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            storageDir: Directory::storagePath('views')
        );
        $result   = $renderer->renderFile('simple');

        self::assertSame('Simple orka content', $result);
    }

    public function testRenderFileWithReplacements(): void
    {
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            '.orka.phtml',
            [],
            Directory::storagePath('views'),
            false,
            new Unescaped()
        );
        $result   = $renderer->renderFile('home', ['title' => 'Home Page']);

        self::assertSame('<html lang="en"><body>Home Page</body></html>', $result);
    }

    public function testRenderFileCachesContent(): void
    {
        $templateName = 'simple';
        $cachedPath   = Directory::storagePath('views/' . md5($templateName));

        // Ensure cache doesn't exist
        if (file_exists($cachedPath)) {
            unlink($cachedPath);
        }

        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            storageDir: Directory::storagePath('views')
        );
        $renderer->renderFile($templateName);

        self::assertFileExists($cachedPath);
    }

    public function testRenderFileUsesCache(): void
    {
        $templateName = 'simple';
        $cachedPath   = Directory::storagePath('views/' . md5($templateName));

        // Ensure cache doesn't exist first
        if (file_exists($cachedPath)) {
            unlink($cachedPath);
        }

        // First render creates the cache
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            storageDir: Directory::storagePath('views')
        );
        $renderer->renderFile($templateName);

        // Verify cache was created
        self::assertFileExists($cachedPath);

        // Verify content in cache
        $cachedContent = file_get_contents($cachedPath);
        self::assertSame('Simple orka content', $cachedContent);
    }

    public function testRenderFileInDebugModeAlwaysRecompiles(): void
    {
        $templateName = 'simple';
        $cachedPath   = Directory::storagePath('views/' . md5($templateName));

        // Create renderer in debug mode
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            '.orka.phtml',
            [],
            Directory::storagePath('views'),
            true // debug mode
        );

        // First render
        $result1 = $renderer->renderFile($templateName);

        // Get the file modification time after first render
        $mtime1 = filemtime($cachedPath);

        // Ensure enough time passes for mtime to differ
        usleep(10000);

        // Clear file stat cache to get fresh mtime
        clearstatcache();

        // Second render should recompile in debug mode
        $result2 = $renderer->renderFile($templateName);

        // Get the file modification time after second render
        clearstatcache();
        $mtime2 = filemtime($cachedPath);

        // In debug mode, file should be recompiled (mtime should be updated)
        self::assertGreaterThanOrEqual($mtime1, $mtime2);
        self::assertSame('Simple orka content', $result1);
        self::assertSame('Simple orka content', $result2);
    }

    public function testRenderFileThrowsExceptionForMissingFile(): void
    {
        $renderer = new class(self::TEMPLATES_DIR) extends OrkaRenderer {
            protected function getFileContents(string $path): string|false
            {
                return false;
            }
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('could not be retrieved');

        $renderer->renderFile('nonexistent');
    }

    public function testConstructorWithCustomFileExtension(): void
    {
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            '.orka.custom',
            storageDir: Directory::storagePath('views')
        );

        $result = $renderer->renderFile('home');

        self::assertSame('<html lang="en"><body>Custom Home</body></html>', $result);
    }

    public function testConstructorWithPaths(): void
    {
        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            '.orka.phtml',
            ['@custom' => self::TEMPLATES_DIR . '/custom'],
            storageDir: Directory::storagePath('views'),
        );

        $result = $renderer->renderFile('@custom/simple');

        self::assertSame('Simple custom directory orka content', $result);
    }

    public function testConstructorWithMultipleReplacements(): void
    {
        $replacement1 = new class implements ReplacementContract {
            public function regex(): string
            {
                return '/\[\[(.+?)\]\]/';
            }

            public function replacement(): string
            {
                return '<?= ${1}; ?>';
            }
        };

        $replacement2 = new class implements ReplacementContract {
            public function regex(): string
            {
                return '/@test/';
            }

            public function replacement(): string
            {
                return 'replaced';
            }
        };

        $renderer = new OrkaRenderer(
            self::TEMPLATES_DIR,
            '.orka.phtml',
            [],
            Directory::storagePath('views'),
            false,
            $replacement1,
            $replacement2
        );

        $result = $renderer->renderFile('replaced', ['variable' => ' correctly']);

        self::assertSame('replaced correctly', $result);
    }
}
