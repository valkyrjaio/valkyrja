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

use Valkyrja\Filesystem\Data\InMemoryFile;
use Valkyrja\Filesystem\Data\InMemoryMetadata;
use Valkyrja\Filesystem\Enum\Visibility;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;
use Valkyrja\Filesystem\Throwable\Exception\UnableToReadContentsException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\RuntimeException;

use function fclose;
use function fopen;
use function fwrite;
use function rewind;

class InMemoryFilesystemTest extends TestCase
{
    protected InMemoryFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = new InMemoryFilesystem();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(FilesystemContract::class, $this->filesystem);
    }

    public function testConstructorWithFiles(): void
    {
        $file       = new InMemoryFile('test.txt', 'contents');
        $filesystem = new InMemoryFilesystem($file);

        self::assertTrue($filesystem->exists('test.txt'));
        self::assertSame('contents', $filesystem->read('test.txt'));
    }

    public function testExistsReturnsFalseForNonExistentFile(): void
    {
        self::assertFalse($this->filesystem->exists('non-existent.txt'));
    }

    public function testExistsReturnsTrueForExistingFile(): void
    {
        $this->filesystem->write('test.txt', 'contents');

        self::assertTrue($this->filesystem->exists('test.txt'));
    }

    public function testReadReturnsFileContents(): void
    {
        $this->filesystem->write('test.txt', 'file contents');

        self::assertSame('file contents', $this->filesystem->read('test.txt'));
    }

    public function testReadThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(UnableToReadContentsException::class);

        $this->filesystem->read('non-existent.txt');
    }

    public function testWriteCreatesFile(): void
    {
        $result = $this->filesystem->write('test.txt', 'contents');

        self::assertTrue($result);
        self::assertTrue($this->filesystem->exists('test.txt'));
        self::assertSame('contents', $this->filesystem->read('test.txt'));
    }

    public function testWriteStreamCreatesFile(): void
    {
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'stream contents');
        rewind($resource);

        $result = $this->filesystem->writeStream('test.txt', $resource);

        self::assertTrue($result);
        self::assertTrue($this->filesystem->exists('test.txt'));
        self::assertSame('stream contents', $this->filesystem->read('test.txt'));

        fclose($resource);
    }

    public function testWriteStreamThrowsExceptionOnReadFailure(): void
    {
        $filesystem = new class extends InMemoryFilesystem {
            protected function readFromResource($resource, int $length): string|false
            {
                return false;
            }
        };

        $resource = fopen('php://memory', 'r+');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to read provided resource');

        try {
            $filesystem->writeStream('test.txt', $resource);
        } finally {
            fclose($resource);
        }
    }

    public function testUpdateUpdatesFile(): void
    {
        $this->filesystem->write('test.txt', 'original');
        $result = $this->filesystem->update('test.txt', 'updated');

        self::assertTrue($result);
        self::assertSame('updated', $this->filesystem->read('test.txt'));
    }

    public function testUpdateStreamUpdatesFile(): void
    {
        $this->filesystem->write('test.txt', 'original');

        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'updated stream');
        rewind($resource);

        $result = $this->filesystem->updateStream('test.txt', $resource);

        self::assertTrue($result);
        self::assertSame('updated stream', $this->filesystem->read('test.txt'));

        fclose($resource);
    }

    public function testPutCreatesOrUpdatesFile(): void
    {
        $result1 = $this->filesystem->put('test.txt', 'contents');

        self::assertTrue($result1);
        self::assertSame('contents', $this->filesystem->read('test.txt'));

        $result2 = $this->filesystem->put('test.txt', 'updated');

        self::assertTrue($result2);
        self::assertSame('updated', $this->filesystem->read('test.txt'));
    }

    public function testPutStreamCreatesOrUpdatesFile(): void
    {
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'stream contents');
        rewind($resource);

        $result = $this->filesystem->putStream('test.txt', $resource);

        self::assertTrue($result);
        self::assertSame('stream contents', $this->filesystem->read('test.txt'));

        fclose($resource);
    }

    public function testRenameMovesFile(): void
    {
        $this->filesystem->write('old.txt', 'contents');

        $result = $this->filesystem->rename('old.txt', 'new.txt');

        self::assertTrue($result);
        self::assertFalse($this->filesystem->exists('old.txt'));
        self::assertTrue($this->filesystem->exists('new.txt'));
        self::assertSame('contents', $this->filesystem->read('new.txt'));
    }

    public function testRenameReturnsFalseWhenDestinationExists(): void
    {
        $this->filesystem->write('old.txt', 'old contents');
        $this->filesystem->write('new.txt', 'new contents');

        $result = $this->filesystem->rename('old.txt', 'new.txt');

        self::assertFalse($result);
    }

    public function testRenameReturnsFalseWhenSourceDoesNotExist(): void
    {
        $result = $this->filesystem->rename('non-existent.txt', 'new.txt');

        self::assertFalse($result);
    }

    public function testCopyCopiesFile(): void
    {
        $this->filesystem->write('source.txt', 'contents');

        $result = $this->filesystem->copy('source.txt', 'destination.txt');

        self::assertTrue($result);
        self::assertTrue($this->filesystem->exists('source.txt'));
        self::assertTrue($this->filesystem->exists('destination.txt'));
        self::assertSame('contents', $this->filesystem->read('destination.txt'));
    }

    public function testCopyReturnsFalseWhenDestinationExists(): void
    {
        $this->filesystem->write('source.txt', 'source contents');
        $this->filesystem->write('destination.txt', 'dest contents');

        $result = $this->filesystem->copy('source.txt', 'destination.txt');

        self::assertFalse($result);
    }

    public function testCopyReturnsFalseWhenSourceDoesNotExist(): void
    {
        $result = $this->filesystem->copy('non-existent.txt', 'destination.txt');

        self::assertFalse($result);
    }

    public function testDeleteRemovesFile(): void
    {
        $this->filesystem->write('test.txt', 'contents');

        $result = $this->filesystem->delete('test.txt');

        self::assertTrue($result);
        self::assertFalse($this->filesystem->exists('test.txt'));
    }

    public function testMetadataReturnsFileMetadata(): void
    {
        $metadata = new InMemoryMetadata('text/plain', 100, 'public');
        $file     = new InMemoryFile('test.txt', 'contents', $metadata);

        $filesystem = new InMemoryFilesystem($file);

        self::assertSame(
            ['mimetype' => 'text/plain', 'size' => 100, 'visibility' => 'public'],
            $filesystem->metadata('test.txt')
        );
    }

    public function testMetadataReturnsNullForNonExistentFile(): void
    {
        self::assertNull($this->filesystem->metadata('non-existent.txt'));
    }

    public function testMimetypeReturnsFileMimetype(): void
    {
        $metadata = new InMemoryMetadata('text/plain');
        $file     = new InMemoryFile('test.txt', 'contents', $metadata);

        $filesystem = new InMemoryFilesystem($file);

        self::assertSame('text/plain', $filesystem->mimetype('test.txt'));
    }

    public function testMimetypeReturnsNullForNonExistentFile(): void
    {
        self::assertNull($this->filesystem->mimetype('non-existent.txt'));
    }

    public function testSizeReturnsFileSize(): void
    {
        $metadata = new InMemoryMetadata(size: 1024);
        $file     = new InMemoryFile('test.txt', 'contents', $metadata);

        $filesystem = new InMemoryFilesystem($file);

        self::assertSame(1024, $filesystem->size('test.txt'));
    }

    public function testSizeReturnsNullForNonExistentFile(): void
    {
        self::assertNull($this->filesystem->size('non-existent.txt'));
    }

    public function testTimestampReturnsFileTimestamp(): void
    {
        $file = new InMemoryFile('test.txt', 'contents', timestamp: 1234567890);

        $filesystem = new InMemoryFilesystem($file);

        self::assertSame(1234567890, $filesystem->timestamp('test.txt'));
    }

    public function testTimestampReturnsNullForNonExistentFile(): void
    {
        self::assertNull($this->filesystem->timestamp('non-existent.txt'));
    }

    public function testVisibilityReturnsFileVisibility(): void
    {
        $metadata = new InMemoryMetadata(visibility: 'public');
        $file     = new InMemoryFile('test.txt', 'contents', $metadata);

        $filesystem = new InMemoryFilesystem($file);

        self::assertSame('public', $filesystem->visibility('test.txt'));
    }

    public function testVisibilityReturnsNullForNonExistentFile(): void
    {
        self::assertNull($this->filesystem->visibility('non-existent.txt'));
    }

    public function testSetVisibilityChangesVisibility(): void
    {
        $this->filesystem->write('test.txt', 'contents');

        $result = $this->filesystem->setVisibility('test.txt', Visibility::PUBLIC);

        self::assertTrue($result);
        self::assertSame('public', $this->filesystem->visibility('test.txt'));
    }

    public function testSetVisibilityReturnsFalseForNonExistentFile(): void
    {
        $result = $this->filesystem->setVisibility('non-existent.txt', Visibility::PUBLIC);

        self::assertFalse($result);
    }

    public function testSetVisibilityPublicSetsPublicVisibility(): void
    {
        $this->filesystem->write('test.txt', 'contents');

        $result = $this->filesystem->setVisibilityPublic('test.txt');

        self::assertTrue($result);
        self::assertSame('public', $this->filesystem->visibility('test.txt'));
    }

    public function testSetVisibilityPrivateSetsPrivateVisibility(): void
    {
        $this->filesystem->write('test.txt', 'contents');

        $result = $this->filesystem->setVisibilityPrivate('test.txt');

        self::assertTrue($result);
        self::assertSame('private', $this->filesystem->visibility('test.txt'));
    }

    public function testCreateDirCreatesDirectory(): void
    {
        $result = $this->filesystem->createDir('my-directory');

        self::assertTrue($result);
        self::assertTrue($this->filesystem->exists('my-directory'));
    }

    public function testDeleteDirRemovesDirectoryAndContents(): void
    {
        $this->filesystem->write('dir/file1.txt', 'contents1');
        $this->filesystem->write('dir/file2.txt', 'contents2');
        $this->filesystem->write('other/file.txt', 'other');

        $result = $this->filesystem->deleteDir('dir');

        self::assertTrue($result);
        self::assertFalse($this->filesystem->exists('dir/file1.txt'));
        self::assertFalse($this->filesystem->exists('dir/file2.txt'));
        self::assertTrue($this->filesystem->exists('other/file.txt'));
    }

    public function testListContentsReturnsAllFiles(): void
    {
        $this->filesystem->write('file1.txt', 'contents1');
        $this->filesystem->write('file2.txt', 'contents2');

        $contents = $this->filesystem->listContents();

        self::assertCount(2, $contents);
    }

    public function testListContentsFiltersbyDirectory(): void
    {
        $this->filesystem->write('dir/file1.txt', 'contents1');
        $this->filesystem->write('dir/file2.txt', 'contents2');
        $this->filesystem->write('other/file.txt', 'other');

        $contents = $this->filesystem->listContents('dir');

        self::assertCount(2, $contents);
    }
}
