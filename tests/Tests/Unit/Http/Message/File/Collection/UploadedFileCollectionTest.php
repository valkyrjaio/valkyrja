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

namespace Valkyrja\Tests\Unit\Http\Message\File\Collection;

use Valkyrja\Http\Message\File\Collection\Contract\UploadedFileCollectionContract;
use Valkyrja\Http\Message\File\Collection\UploadedFileCollection;
use Valkyrja\Http\Message\File\Contract\UploadedFileContract;
use Valkyrja\Http\Message\File\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UploadedFileCollectionTest extends TestCase
{
    protected UploadedFileCollection $fileData;

    protected UploadedFileContract $file;

    protected UploadedFileContract $file2;

    protected function setUp(): void
    {
        $this->file     = self::createStub(UploadedFileContract::class);
        $this->file2    = self::createStub(UploadedFileContract::class);
        $this->fileData = new UploadedFileCollection(avatar: $this->file, document: $this->file2);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(UploadedFileCollectionContract::class, $this->fileData);
    }

    public function testConstructorWithNoFiles(): void
    {
        $fileData = new UploadedFileCollection();

        self::assertEmpty($fileData->getFiles());
    }

    public function testConstructorWithFiles(): void
    {
        self::assertSame($this->file, $this->fileData->getFile('avatar'));
        self::assertSame($this->file2, $this->fileData->getFile('document'));
    }

    public function testConstructorWithNestedFileData(): void
    {
        $nested   = new UploadedFileCollection(inner: $this->file);
        $fileData = new UploadedFileCollection(nested: $nested);

        self::assertSame($nested, $fileData->getFile('nested'));
    }

    public function testHasFileReturnsTrue(): void
    {
        self::assertTrue($this->fileData->hasFile('avatar'));
        self::assertTrue($this->fileData->hasFile('document'));
    }

    public function testHasFileReturnsFalse(): void
    {
        self::assertFalse($this->fileData->hasFile('nonexistent'));
    }

    public function testGetFileReturnsFile(): void
    {
        self::assertSame($this->file, $this->fileData->getFile('avatar'));
    }

    public function testGetFileReturnsNullForMissing(): void
    {
        self::assertNull($this->fileData->getFile('nonexistent'));
    }

    public function testGetFiles(): void
    {
        $files = $this->fileData->getFiles();

        self::assertCount(2, $files);
        self::assertSame($this->file, $files['avatar']);
        self::assertSame($this->file2, $files['document']);
    }

    public function testOnlyFiles(): void
    {
        $only = $this->fileData->onlyFiles('avatar');

        self::assertCount(1, $only);
        self::assertSame($this->file, $only['avatar']);
        self::assertArrayNotHasKey('document', $only);
    }

    public function testOnlyFilesWithMultipleNames(): void
    {
        $fileMock3 = self::createStub(UploadedFileContract::class);
        $fileData  = new UploadedFileCollection(a: $this->file, b: $this->file2, c: $fileMock3);
        $only      = $fileData->onlyFiles('a', 'c');

        self::assertCount(2, $only);
        self::assertSame($this->file, $only['a']);
        self::assertSame($fileMock3, $only['c']);
        self::assertArrayNotHasKey('b', $only);
    }

    public function testOnlyFilesWithNonexistentNames(): void
    {
        $only = $this->fileData->onlyFiles('nonexistent');

        self::assertEmpty($only);
    }

    public function testExceptFiles(): void
    {
        $except = $this->fileData->exceptFiles('avatar');

        self::assertCount(1, $except);
        self::assertSame($this->file2, $except['document']);
        self::assertArrayNotHasKey('avatar', $except);
    }

    public function testExceptFilesWithMultipleNames(): void
    {
        $fileMock3 = self::createStub(UploadedFileContract::class);
        $fileData  = new UploadedFileCollection(a: $this->file, b: $this->file2, c: $fileMock3);
        $except    = $fileData->exceptFiles('a', 'c');

        self::assertCount(1, $except);
        self::assertSame($this->file2, $except['b']);
    }

    public function testExceptFilesWithNonexistentNames(): void
    {
        $except = $this->fileData->exceptFiles('nonexistent');

        self::assertCount(2, $except);
    }

    public function testWithFilesReturnsNewInstance(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $new     = $this->fileData->withFiles(['new' => $newFile]);

        self::assertNotSame($this->fileData, $new);
        self::assertSame($newFile, $new->getFile('new'));
        self::assertNull($new->getFile('avatar'));
    }

    public function testWithFilesDoesNotModifyOriginal(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $this->fileData->withFiles(['new' => $newFile]);

        self::assertSame($this->file, $this->fileData->getFile('avatar'));
        self::assertSame($this->file2, $this->fileData->getFile('document'));
    }

    public function testWithFilesThrowsForInvalidFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /* @phpstan-ignore-next-line */
        $this->fileData->withFiles(['invalid' => 'not-a-file']);
    }

    public function testWithAddedFilesReturnsNewInstance(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $new     = $this->fileData->withAddedFiles(extra: $newFile);

        self::assertNotSame($this->fileData, $new);
        self::assertSame($this->file, $new->getFile('avatar'));
        self::assertSame($this->file2, $new->getFile('document'));
        self::assertSame($newFile, $new->getFile('extra'));
    }

    public function testWithAddedFilesDoesNotModifyOriginal(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $this->fileData->withAddedFiles(extra: $newFile);

        self::assertFalse($this->fileData->hasFile('extra'));
    }

    public function testWithAddedFilesWithNestedFileData(): void
    {
        $nested = new UploadedFileCollection(inner: $this->file);
        $new    = $this->fileData->withAddedFiles(nested: $nested);

        self::assertSame($nested, $new->getFile('nested'));
    }

    public function testFromArray(): void
    {
        $file     = self::createStub(UploadedFileContract::class);
        $fileData = $this->fileData->fromArray(['uploaded' => $file]);

        self::assertSame($file, $fileData->getFile('uploaded'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $file     = self::createStub(UploadedFileContract::class);
        $fileData = $this->fileData->fromArray(['nested' => [$file]]);

        $nested = $fileData->getFile('nested');

        self::assertInstanceOf(UploadedFileCollection::class, $nested);
        self::assertSame($file, $nested->getFile(0));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->fileData->fromArray(['invalid' => 'not-a-file']);
    }

    public function testHasFileWithIntKey(): void
    {
        $fileData = new UploadedFileCollection($this->file, $this->file2);

        self::assertTrue($fileData->hasFile(0));
        self::assertTrue($fileData->hasFile(1));
        self::assertFalse($fileData->hasFile(2));
    }

    public function testGetFileWithIntKey(): void
    {
        $fileData = new UploadedFileCollection($this->file, $this->file2);

        self::assertSame($this->file, $fileData->getFile(0));
        self::assertSame($this->file2, $fileData->getFile(1));
        self::assertNull($fileData->getFile(2));
    }
}
