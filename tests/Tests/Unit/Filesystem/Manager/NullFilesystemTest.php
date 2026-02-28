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

namespace Valkyrja\Tests\Unit\Filesystem\Manager;

use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\NullFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;

final class NullFilesystemTest extends TestCase
{
    protected NullFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = new NullFilesystem();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(FilesystemContract::class, $this->filesystem);
    }

    public function testExistsAlwaysReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->exists('any-path'));
        self::assertTrue($this->filesystem->exists(''));
    }

    public function testReadReturnsEmptyString(): void
    {
        self::assertSame('', $this->filesystem->read('any-path'));
    }

    public function testWriteReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->write('path', 'contents'));
    }

    public function testWriteStreamReturnsTrue(): void
    {
        $resource = fopen('php://memory', 'r');

        self::assertTrue($this->filesystem->writeStream('path', $resource));

        fclose($resource);
    }

    public function testUpdateReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->update('path', 'contents'));
    }

    public function testUpdateStreamReturnsTrue(): void
    {
        $resource = fopen('php://memory', 'r');

        self::assertTrue($this->filesystem->updateStream('path', $resource));

        fclose($resource);
    }

    public function testPutReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->put('path', 'contents'));
    }

    public function testPutStreamReturnsTrue(): void
    {
        $resource = fopen('php://memory', 'r');

        self::assertTrue($this->filesystem->putStream('path', $resource));

        fclose($resource);
    }

    public function testRenameReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->rename('old-path', 'new-path'));
    }

    public function testCopyReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->copy('source', 'destination'));
    }

    public function testDeleteReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->delete('path'));
    }

    public function testMetadataReturnsNull(): void
    {
        self::assertSame([], $this->filesystem->metadata('path'));
    }

    public function testMimetypeReturnsNull(): void
    {
        self::assertSame('', $this->filesystem->mimetype('path'));
    }

    public function testSizeReturnsNull(): void
    {
        self::assertSame(0, $this->filesystem->size('path'));
    }

    public function testTimestampReturnsNull(): void
    {
        self::assertSame(0, $this->filesystem->timestamp('path'));
    }

    public function testVisibilityReturnsNull(): void
    {
        self::assertSame(Visibility::PUBLIC, $this->filesystem->visibility('path'));
    }

    public function testSetVisibilityReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->setVisibility('path', Visibility::PUBLIC));
        self::assertTrue($this->filesystem->setVisibility('path', Visibility::PRIVATE));
    }

    public function testSetVisibilityPublicReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->setVisibilityPublic('path'));
    }

    public function testSetVisibilityPrivateReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->setVisibilityPrivate('path'));
    }

    public function testCreateDirReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->createDir('path'));
    }

    public function testDeleteDirReturnsTrue(): void
    {
        self::assertTrue($this->filesystem->deleteDir('path'));
    }

    public function testListContentsReturnsEmptyArray(): void
    {
        self::assertSame([], $this->filesystem->listContents());
        self::assertSame([], $this->filesystem->listContents('directory'));
        self::assertSame([], $this->filesystem->listContents('directory', true));
    }
}
