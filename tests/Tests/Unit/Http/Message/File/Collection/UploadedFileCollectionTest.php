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
        $this->fileData = new UploadedFileCollection(['avatar' => $this->file, 'document' => $this->file2]);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(UploadedFileCollectionContract::class, $this->fileData);
    }

    public function testConstructorWithNoFiles(): void
    {
        $fileData = new UploadedFileCollection();

        self::assertEmpty($fileData->getAll());
    }

    public function testConstructorWithFiles(): void
    {
        self::assertSame($this->file, $this->fileData->get('avatar'));
        self::assertSame($this->file2, $this->fileData->get('document'));
    }

    public function testConstructorWithNestedFileData(): void
    {
        $nested   = new UploadedFileCollection(['inner' => $this->file]);
        $fileData = new UploadedFileCollection(['nested' => $nested]);

        self::assertSame($nested, $fileData->get('nested'));
    }

    public function testHasFileReturnsTrue(): void
    {
        self::assertTrue($this->fileData->has('avatar'));
        self::assertTrue($this->fileData->has('document'));
    }

    public function testHasFileReturnsFalse(): void
    {
        self::assertFalse($this->fileData->has('nonexistent'));
    }

    public function testGetFileReturnsFile(): void
    {
        self::assertSame($this->file, $this->fileData->get('avatar'));
    }

    public function testGetFileReturnsNullForMissing(): void
    {
        self::assertNull($this->fileData->get('nonexistent'));
    }

    public function testGetFiles(): void
    {
        $files = $this->fileData->getAll();

        self::assertCount(2, $files);
        self::assertSame($this->file, $files['avatar']);
        self::assertSame($this->file2, $files['document']);
    }

    public function testOnlyFiles(): void
    {
        $only = $this->fileData->getOnly('avatar');

        self::assertCount(1, $only);
        self::assertSame($this->file, $only['avatar']);
        self::assertArrayNotHasKey('document', $only);
    }

    public function testOnlyFilesWithMultipleNames(): void
    {
        $fileMock3 = self::createStub(UploadedFileContract::class);
        $fileData  = new UploadedFileCollection(['a' => $this->file, 'b' => $this->file2, 'c' => $fileMock3]);
        $only      = $fileData->getOnly('a', 'c');

        self::assertCount(2, $only);
        self::assertSame($this->file, $only['a']);
        self::assertSame($fileMock3, $only['c']);
        self::assertArrayNotHasKey('b', $only);
    }

    public function testOnlyFilesWithNonexistentNames(): void
    {
        $only = $this->fileData->getOnly('nonexistent');

        self::assertEmpty($only);
    }

    public function testExceptFiles(): void
    {
        $except = $this->fileData->getAllExcept('avatar');

        self::assertCount(1, $except);
        self::assertSame($this->file2, $except['document']);
        self::assertArrayNotHasKey('avatar', $except);
    }

    public function testExceptFilesWithMultipleNames(): void
    {
        $fileMock3 = self::createStub(UploadedFileContract::class);
        $fileData  = new UploadedFileCollection(['a' => $this->file, 'b' => $this->file2, 'c' => $fileMock3]);
        $except    = $fileData->getAllExcept('a', 'c');

        self::assertCount(1, $except);
        self::assertSame($this->file2, $except['b']);
    }

    public function testExceptFilesWithNonexistentNames(): void
    {
        $except = $this->fileData->getAllExcept('nonexistent');

        self::assertCount(2, $except);
    }

    public function testWithFilesReturnsNewInstance(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $new     = $this->fileData->with(['new' => $newFile]);

        self::assertNotSame($this->fileData, $new);
        self::assertSame($newFile, $new->get('new'));
        self::assertNull($new->get('avatar'));
    }

    public function testWithFilesDoesNotModifyOriginal(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $this->fileData->with(['new' => $newFile]);

        self::assertSame($this->file, $this->fileData->get('avatar'));
        self::assertSame($this->file2, $this->fileData->get('document'));
    }

    public function testWithFilesThrowsForInvalidFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /* @phpstan-ignore-next-line */
        $this->fileData->with(['invalid' => 'not-a-file']);
    }

    public function testWithAddedFilesReturnsNewInstance(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $new     = $this->fileData->withAdded(['extra' => $newFile]);

        self::assertNotSame($this->fileData, $new);
        self::assertSame($this->file, $new->get('avatar'));
        self::assertSame($this->file2, $new->get('document'));
        self::assertSame($newFile, $new->get('extra'));
    }

    public function testWithAddedFilesDoesNotModifyOriginal(): void
    {
        $newFile = self::createStub(UploadedFileContract::class);
        $this->fileData->withAdded(['extra' => $newFile]);

        self::assertFalse($this->fileData->has('extra'));
    }

    public function testWithAddedFilesWithNestedFileData(): void
    {
        $nested = new UploadedFileCollection(['inner' => $this->file]);
        $new    = $this->fileData->withAdded(['nested' => $nested]);

        self::assertSame($nested, $new->get('nested'));
    }

    public function testFromArray(): void
    {
        $file     = self::createStub(UploadedFileContract::class);
        $fileData = UploadedFileCollection::fromArray(['uploaded' => $file]);

        self::assertSame($file, $fileData->get('uploaded'));
    }

    public function testFromArrayWithNestedArray(): void
    {
        $file     = self::createStub(UploadedFileContract::class);
        $fileData = UploadedFileCollection::fromArray(['nested' => [$file]]);

        $nested = $fileData->get('nested');

        self::assertInstanceOf(UploadedFileCollection::class, $nested);
        self::assertSame($file, $nested->get(0));
    }

    public function testFromArrayThrowsForInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UploadedFileCollection::fromArray(['invalid' => 'not-a-file']);
    }

    public function testHasFileWithIntKey(): void
    {
        $fileData = new UploadedFileCollection([$this->file, $this->file2]);

        self::assertTrue($fileData->has(0));
        self::assertTrue($fileData->has(1));
        self::assertFalse($fileData->has(2));
    }

    public function testGetFileWithIntKey(): void
    {
        $fileData = new UploadedFileCollection([1 => $this->file, 2 => $this->file2]);

        self::assertNull($fileData->get(0));
        self::assertSame($this->file, $fileData->get(1));
        self::assertSame($this->file2, $fileData->get(2));
        self::assertNull($fileData->get(3));
    }
}
