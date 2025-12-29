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
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Directory support class.
 *
 * @author Melech Mizrachi
 */
class DirectoryTest extends TestCase
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
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Directory::$BASE_PATH = $this->basePath;
    }

    /**
     * Test the basePath directory helper method.
     *
     * @return void
     */
    public function testBasePath(): void
    {
        self::assertSame($this->basePath, Directory::basePath());
    }

    /**
     * Test the basePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testBasePathSubPath(): void
    {
        $expected = $this->basePath . $this->subPath;

        self::assertSame($expected, Directory::basePath($this->subPath));
    }

    /**
     * Test the appPath directory helper method.
     *
     * @return void
     */
    public function testAppPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$APP_PATH;

        self::assertSame($expected, Directory::appPath());
    }

    /**
     * Test the appPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testAppPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$APP_PATH . $this->subPath;

        self::assertSame($expected, Directory::appPath($this->subPath));
    }

    /**
     * Test the cachePath directory helper method.
     *
     * @return void
     */
    public function testCachePath(): void
    {
        $expected = $this->basePath
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH;

        self::assertSame($expected, Directory::cachePath());
    }

    /**
     * Test the cachePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testCachePathSubPath(): void
    {
        $expected = $this->basePath
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$FRAMEWORK_STORAGE_PATH
            . Directory::DIRECTORY_SEPARATOR
            . Directory::$CACHE_PATH
            . $this->subPath;

        self::assertSame($expected, Directory::cachePath($this->subPath));
    }

    /**
     * Test the configPath directory helper method.
     *
     * @return void
     */
    public function testConfigPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH;

        self::assertSame($expected, Directory::configPath());
    }

    /**
     * Test the configPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testConfigPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$CONFIG_PATH . $this->subPath;

        self::assertSame($expected, Directory::configPath($this->subPath));
    }

    /**
     * Test the envPath directory helper method.
     *
     * @return void
     */
    public function testEnvPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$ENV_PATH;

        self::assertSame($expected, Directory::envPath());
    }

    /**
     * Test the envPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testEnvPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$ENV_PATH . $this->subPath;

        self::assertSame($expected, Directory::envPath($this->subPath));
    }

    /**
     * Test the publicPath directory helper method.
     *
     * @return void
     */
    public function testPublicPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH;

        self::assertSame($expected, Directory::publicPath());
    }

    /**
     * Test the publicPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testPublicPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$PUBLIC_PATH . $this->subPath;

        self::assertSame($expected, Directory::publicPath($this->subPath));
    }

    /**
     * Test the resourcesPath directory helper method.
     *
     * @return void
     */
    public function testResourcesPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH;

        self::assertSame($expected, Directory::resourcesPath());
    }

    /**
     * Test the resourcesPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testResourcesPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$RESOURCES_PATH . $this->subPath;

        self::assertSame($expected, Directory::resourcesPath($this->subPath));
    }

    /**
     * Test the storagePath directory helper method.
     *
     * @return void
     */
    public function testStoragePath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH;

        self::assertSame($expected, Directory::storagePath());
    }

    /**
     * Test the storagePath directory helper method with a sub path.
     *
     * @return void
     */
    public function testStoragePathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$STORAGE_PATH . $this->subPath;

        self::assertSame($expected, Directory::storagePath($this->subPath));
    }

    /**
     * Test the testsPath directory helper method.
     *
     * @return void
     */
    public function testTestsPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$TESTS_PATH;

        self::assertSame($expected, Directory::testsPath());
    }

    /**
     * Test the testsPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testTestsPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$TESTS_PATH . $this->subPath;

        self::assertSame($expected, Directory::testsPath($this->subPath));
    }

    /**
     * Test the vendorPath directory helper method.
     *
     * @return void
     */
    public function testVendorPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH;

        self::assertSame($expected, Directory::vendorPath());
    }

    /**
     * Test the vendorPath directory helper method with a sub path.
     *
     * @return void
     */
    public function testVendorPathSubPath(): void
    {
        $expected = $this->basePath . Directory::DIRECTORY_SEPARATOR . Directory::$VENDOR_PATH . $this->subPath;

        self::assertSame($expected, Directory::vendorPath($this->subPath));
    }

    /**
     * Test the path helper.
     *
     * @return void
     */
    public function testPath(): void
    {
        self::assertSame($this->subPath, Directory::path($this->subPath));
    }

    /**
     * Test the path helper with a path that has no forward slash.
     *
     * @return void
     */
    public function testPathNoForwardSlash(): void
    {
        self::assertSame($this->subPath, Directory::path('sub/path'));
    }
}
