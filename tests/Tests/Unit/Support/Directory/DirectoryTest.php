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

namespace Valkyrja\Tests\Unit\Support\Directory;

use Valkyrja\Support\Directory\Directory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Directory support class.
 */
final class DirectoryTest extends TestCase
{
    /**
     * The base path.
     *
     * @var string
     */
    protected string $basePath = '/base/path';

    /**
     * The sub path.
     *
     * @var string
     */
    protected string $subPath = '/sub/path';

    /**
     * Setup the test.
     */
    protected function setUp(): void
    {
        Directory::$basePath = $this->basePath;
    }

    /**
     * Test the basePath directory helper method.
     */
    public function testBasePath(): void
    {
        self::assertSame($this->basePath, Directory::basePath());
    }

    /**
     * Test the basePath directory helper method with a sub path.
     */
    public function testBasePathSubPath(): void
    {
        $expected = $this->basePath . $this->subPath;

        self::assertSame($expected, Directory::basePath($this->subPath));
    }

    /**
     * Test the appPath directory helper method.
     */
    public function testAppPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$appPath;

        self::assertSame($expected, Directory::appPath());
    }

    /**
     * Test the appPath directory helper method with a sub path.
     */
    public function testAppPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$appPath . $this->subPath;

        self::assertSame($expected, Directory::appPath($this->subPath));
    }

    /**
     * Test the cachePath directory helper method.
     */
    public function testFrameworkStorageCachePath(): void
    {
        $expected = $this->basePath
            . '/'
            . Directory::$storagePath
            . '/'
            . Directory::$frameworkStoragePath
            . '/'
            . Directory::$cacheStoragePath;

        self::assertSame($expected, Directory::frameworkStorageCachePath());
    }

    /**
     * Test the cachePath directory helper method with a sub path.
     */
    public function testFrameworkStorageCachePathSubPath(): void
    {
        $expected = $this->basePath
            . '/'
            . Directory::$storagePath
            . '/'
            . Directory::$frameworkStoragePath
            . '/'
            . Directory::$cacheStoragePath
            . $this->subPath;

        self::assertSame($expected, Directory::frameworkStorageCachePath($this->subPath));
    }

    /**
     * Test the dataPath directory helper method.
     */
    public function testDataPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$dataPath;

        self::assertSame($expected, Directory::dataPath());
    }

    /**
     * Test the dataPath directory helper method with a sub path.
     */
    public function testDataPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$dataPath . $this->subPath;

        self::assertSame($expected, Directory::dataPath($this->subPath));
    }

    /**
     * Test the envPath directory helper method.
     */
    public function testEnvPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$envPath;

        self::assertSame($expected, Directory::envPath());
    }

    /**
     * Test the envPath directory helper method with a sub path.
     */
    public function testEnvPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$envPath . $this->subPath;

        self::assertSame($expected, Directory::envPath($this->subPath));
    }

    /**
     * Test the publicPath directory helper method.
     */
    public function testPublicPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$publicPath;

        self::assertSame($expected, Directory::publicPath());
    }

    /**
     * Test the publicPath directory helper method with a sub path.
     */
    public function testPublicPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$publicPath . $this->subPath;

        self::assertSame($expected, Directory::publicPath($this->subPath));
    }

    /**
     * Test the resourcesPath directory helper method.
     */
    public function testResourcesPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$resourcesPath;

        self::assertSame($expected, Directory::resourcesPath());
    }

    /**
     * Test the resourcesPath directory helper method with a sub path.
     */
    public function testResourcesPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$resourcesPath . $this->subPath;

        self::assertSame($expected, Directory::resourcesPath($this->subPath));
    }

    /**
     * Test the storagePath directory helper method.
     */
    public function testStoragePath(): void
    {
        $expected = $this->basePath . '/' . Directory::$storagePath;

        self::assertSame($expected, Directory::storagePath());
    }

    /**
     * Test the storagePath directory helper method with a sub path.
     */
    public function testStoragePathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$storagePath . $this->subPath;

        self::assertSame($expected, Directory::storagePath($this->subPath));
    }

    /**
     * Test the testsPath directory helper method.
     */
    public function testTestsPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$testsPath;

        self::assertSame($expected, Directory::testsPath());
    }

    /**
     * Test the testsPath directory helper method with a sub path.
     */
    public function testTestsPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$testsPath . $this->subPath;

        self::assertSame($expected, Directory::testsPath($this->subPath));
    }

    /**
     * Test the vendorPath directory helper method.
     */
    public function testVendorPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$vendorPath;

        self::assertSame($expected, Directory::vendorPath());
    }

    /**
     * Test the vendorPath directory helper method with a sub path.
     */
    public function testVendorPathSubPath(): void
    {
        $expected = $this->basePath . '/' . Directory::$vendorPath . $this->subPath;

        self::assertSame($expected, Directory::vendorPath($this->subPath));
    }

    /**
     * Test the path helper.
     */
    public function testPath(): void
    {
        self::assertSame($this->subPath, Directory::path($this->subPath));
    }

    /**
     * Test the path helper with a path that has no forward slash.
     */
    public function testPathNoForwardSlash(): void
    {
        self::assertSame($this->subPath, Directory::path('sub/path'));
    }
}
