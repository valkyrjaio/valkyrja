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

namespace Valkyrja\Tests\Unit\Mail\Data;

use Valkyrja\Mail\Data\Attachment;
use Valkyrja\Mail\Data\Contract\AttachmentContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class AttachmentTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $attachment = new Attachment('/path/to/file.pdf');

        self::assertInstanceOf(AttachmentContract::class, $attachment);
    }

    public function testGetPath(): void
    {
        $path       = '/path/to/document.pdf';
        $attachment = new Attachment($path);

        self::assertSame($path, $attachment->getPath());
    }

    public function testGetNameReturnsNullByDefault(): void
    {
        $attachment = new Attachment('/path/to/file.pdf');

        self::assertNull($attachment->getName());
    }

    public function testGetNameReturnsSetName(): void
    {
        $name       = 'invoice.pdf';
        $attachment = new Attachment('/path/to/file.pdf', $name);

        self::assertSame($name, $attachment->getName());
    }

    public function testWithPathReturnsNewInstance(): void
    {
        $attachment    = new Attachment('/original/path.pdf');
        $newAttachment = $attachment->withPath('/new/path.pdf');

        self::assertNotSame($attachment, $newAttachment);
        self::assertSame('/original/path.pdf', $attachment->getPath());
        self::assertSame('/new/path.pdf', $newAttachment->getPath());
    }

    public function testWithPathPreservesName(): void
    {
        $attachment    = new Attachment('/original/path.pdf', 'document.pdf');
        $newAttachment = $attachment->withPath('/new/path.pdf');

        self::assertSame('document.pdf', $newAttachment->getName());
    }

    public function testWithNameReturnsNewInstance(): void
    {
        $attachment    = new Attachment('/path/to/file.pdf', 'original.pdf');
        $newAttachment = $attachment->withName('renamed.pdf');

        self::assertNotSame($attachment, $newAttachment);
        self::assertSame('original.pdf', $attachment->getName());
        self::assertSame('renamed.pdf', $newAttachment->getName());
    }

    public function testWithNamePreservesPath(): void
    {
        $path          = '/path/to/file.pdf';
        $attachment    = new Attachment($path);
        $newAttachment = $attachment->withName('document.pdf');

        self::assertSame($path, $newAttachment->getPath());
    }

    public function testWithNameCanSetToNull(): void
    {
        $attachment    = new Attachment('/path/to/file.pdf', 'document.pdf');
        $newAttachment = $attachment->withName(null);

        self::assertNull($newAttachment->getName());
    }

    public function testWithNameCanSetToNullWithoutArgument(): void
    {
        $attachment    = new Attachment('/path/to/file.pdf', 'document.pdf');
        $newAttachment = $attachment->withName();

        self::assertNull($newAttachment->getName());
    }

    public function testImmutability(): void
    {
        $attachment = new Attachment('/path/to/file.pdf', 'original.pdf');

        $withPath = $attachment->withPath('/new/path.pdf');
        $withName = $attachment->withName('new.pdf');

        // Original should remain unchanged
        self::assertSame('/path/to/file.pdf', $attachment->getPath());
        self::assertSame('original.pdf', $attachment->getName());

        // New instances should have changes
        self::assertSame('/new/path.pdf', $withPath->getPath());
        self::assertSame('new.pdf', $withName->getName());
    }
}
