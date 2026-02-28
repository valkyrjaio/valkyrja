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

use League\Flysystem\DirectoryListing;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToReadFile;
use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\FlysystemFilesystem;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function fopen;

final class FlysystemFilesystemTest extends TestCase
{
    protected MockObject&FilesystemOperator $flysystem;

    protected FlysystemFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->flysystem  = $this->createMock(FilesystemOperator::class);
        $this->filesystem = new FlysystemFilesystem($this->flysystem);
    }

    public function testInstanceOfContract(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertInstanceOf(FilesystemContract::class, $this->filesystem);
    }

    public function testGetFlysystemReturnsOperator(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertSame($this->flysystem, $this->filesystem->getFlysystem());
    }

    /**
     * @throws FilesystemException
     */
    public function testExistsReturnsTrueWhenFileExists(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('has')
            ->with('test.txt')
            ->willReturn(true);

        self::assertTrue($this->filesystem->exists('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testExistsReturnsFalseWhenFileDoesNotExist(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('has')
            ->with('test.txt')
            ->willReturn(false);

        self::assertFalse($this->filesystem->exists('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testReadReturnsFileContents(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('read')
            ->with('test.txt')
            ->willReturn('file contents');

        self::assertSame('file contents', $this->filesystem->read('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testReadThrowsExceptionOnFailure(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('read')
            ->with('test.txt')
            ->willThrowException(UnableToReadFile::fromLocation('test.txt'));

        $this->expectException(FilesystemException::class);

        $this->filesystem->read('test.txt');
    }

    /**
     * @throws FilesystemException
     */
    public function testWriteWritesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('write')
            ->with('test.txt', 'contents');

        self::assertTrue($this->filesystem->write('test.txt', 'contents'));
    }

    /**
     * @throws FilesystemException
     */
    public function testWriteStreamWritesFile(): void
    {
        $resource = fopen('php://memory', 'r');

        $this->flysystem
            ->expects($this->once())
            ->method('writeStream')
            ->with('test.txt', $resource);

        self::assertTrue($this->filesystem->writeStream('test.txt', $resource));

        fclose($resource);
    }

    /**
     * @throws FilesystemException
     */
    public function testUpdateWritesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('write')
            ->with('test.txt', 'updated contents');

        self::assertTrue($this->filesystem->update('test.txt', 'updated contents'));
    }

    /**
     * @throws FilesystemException
     */
    public function testUpdateStreamWritesFile(): void
    {
        $resource = fopen('php://memory', 'r');

        $this->flysystem
            ->expects($this->once())
            ->method('writeStream')
            ->with('test.txt', $resource);

        self::assertTrue($this->filesystem->updateStream('test.txt', $resource));

        fclose($resource);
    }

    /**
     * @throws FilesystemException
     */
    public function testPutWritesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('write')
            ->with('test.txt', 'contents');

        self::assertTrue($this->filesystem->put('test.txt', 'contents'));
    }

    /**
     * @throws FilesystemException
     */
    public function testPutStreamWritesFile(): void
    {
        $resource = fopen('php://memory', 'r');

        $this->flysystem
            ->expects($this->once())
            ->method('writeStream')
            ->with('test.txt', $resource);

        self::assertTrue($this->filesystem->putStream('test.txt', $resource));

        fclose($resource);
    }

    /**
     * @throws FilesystemException
     */
    public function testRenameMovesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('move')
            ->with('old.txt', 'new.txt');

        self::assertTrue($this->filesystem->rename('old.txt', 'new.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testCopyCopiesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('copy')
            ->with('source.txt', 'destination.txt');

        self::assertTrue($this->filesystem->copy('source.txt', 'destination.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testDeleteDeletesFile(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('delete')
            ->with('test.txt');

        self::assertTrue($this->filesystem->delete('test.txt'));
    }

    public function testMetadataReturnsNull(): void
    {
        $this->flysystem->expects($this->never())->method('has');

        self::assertSame([], $this->filesystem->metadata('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testMimetypeReturnsMimeType(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('mimeType')
            ->with('test.txt')
            ->willReturn('text/plain');

        self::assertSame('text/plain', $this->filesystem->mimetype('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testSizeReturnsFileSize(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('fileSize')
            ->with('test.txt')
            ->willReturn(1024);

        self::assertSame(1024, $this->filesystem->size('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testTimestampReturnsLastModified(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('lastModified')
            ->with('test.txt')
            ->willReturn(1234567890);

        self::assertSame(1234567890, $this->filesystem->timestamp('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testVisibilityReturnsVisibility(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('visibility')
            ->with('test.txt')
            ->willReturn('public');

        self::assertSame(Visibility::PUBLIC, $this->filesystem->visibility('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testSetVisibilitySetsVisibility(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('setVisibility')
            ->with('test.txt', 'public');

        self::assertTrue($this->filesystem->setVisibility('test.txt', Visibility::PUBLIC));
    }

    /**
     * @throws FilesystemException
     */
    public function testSetVisibilityPublicSetsPublicVisibility(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('setVisibility')
            ->with('test.txt', 'public');

        self::assertTrue($this->filesystem->setVisibilityPublic('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testSetVisibilityPrivateSetsPrivateVisibility(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('setVisibility')
            ->with('test.txt', 'private');

        self::assertTrue($this->filesystem->setVisibilityPrivate('test.txt'));
    }

    /**
     * @throws FilesystemException
     */
    public function testCreateDirCreatesDirectory(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('createDirectory')
            ->with('my-directory');

        self::assertTrue($this->filesystem->createDir('my-directory'));
    }

    /**
     * @throws FilesystemException
     */
    public function testDeleteDirDeletesDirectory(): void
    {
        $this->flysystem
            ->expects($this->once())
            ->method('deleteDirectory')
            ->with('my-directory');

        self::assertTrue($this->filesystem->deleteDir('my-directory'));
    }

    /**
     * @throws FilesystemException
     */
    public function testListContentsReturnsDirectoryContents(): void
    {
        $fileAttributes = new FileAttributes('test.txt');
        $listing        = new DirectoryListing([$fileAttributes]);

        $this->flysystem
            ->expects($this->once())
            ->method('listContents')
            ->with('', false)
            ->willReturn($listing);

        $contents = $this->filesystem->listContents();

        self::assertCount(1, $contents);
    }

    /**
     * @throws FilesystemException
     */
    public function testListContentsWithDirectoryAndRecursive(): void
    {
        $listing = new DirectoryListing([]);

        $this->flysystem
            ->expects($this->once())
            ->method('listContents')
            ->with('my-directory', true)
            ->willReturn($listing);

        $contents = $this->filesystem->listContents('my-directory', true);

        self::assertSame([], $contents);
    }
}
