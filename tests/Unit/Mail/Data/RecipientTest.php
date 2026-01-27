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

use Valkyrja\Mail\Data\Contract\RecipientContract;
use Valkyrja\Mail\Data\Recipient;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class RecipientTest extends TestCase
{
    public function testInstanceOfContract(): void
    {
        $recipient = new Recipient('test@example.com');

        self::assertInstanceOf(RecipientContract::class, $recipient);
    }

    public function testGetEmail(): void
    {
        $email     = 'user@example.com';
        $recipient = new Recipient($email);

        self::assertSame($email, $recipient->getEmail());
    }

    public function testGetNameReturnsNullByDefault(): void
    {
        $recipient = new Recipient('test@example.com');

        self::assertNull($recipient->getName());
    }

    public function testGetNameReturnsSetName(): void
    {
        $name      = 'John Doe';
        $recipient = new Recipient('john@example.com', $name);

        self::assertSame($name, $recipient->getName());
    }

    public function testWithEmailReturnsNewInstance(): void
    {
        $recipient    = new Recipient('original@example.com');
        $newRecipient = $recipient->withEmail('new@example.com');

        self::assertNotSame($recipient, $newRecipient);
        self::assertSame('original@example.com', $recipient->getEmail());
        self::assertSame('new@example.com', $newRecipient->getEmail());
    }

    public function testWithEmailPreservesName(): void
    {
        $recipient    = new Recipient('original@example.com', 'John Doe');
        $newRecipient = $recipient->withEmail('new@example.com');

        self::assertSame('John Doe', $newRecipient->getName());
    }

    public function testWithNameReturnsNewInstance(): void
    {
        $recipient    = new Recipient('test@example.com', 'Original Name');
        $newRecipient = $recipient->withName('New Name');

        self::assertNotSame($recipient, $newRecipient);
        self::assertSame('Original Name', $recipient->getName());
        self::assertSame('New Name', $newRecipient->getName());
    }

    public function testWithNamePreservesEmail(): void
    {
        $email        = 'test@example.com';
        $recipient    = new Recipient($email);
        $newRecipient = $recipient->withName('John Doe');

        self::assertSame($email, $newRecipient->getEmail());
    }

    public function testWithNameCanSetToNull(): void
    {
        $recipient    = new Recipient('test@example.com', 'John Doe');
        $newRecipient = $recipient->withName(null);

        self::assertNull($newRecipient->getName());
    }

    public function testWithNameCanSetToNullWithoutArgument(): void
    {
        $recipient    = new Recipient('test@example.com', 'John Doe');
        $newRecipient = $recipient->withName();

        self::assertNull($newRecipient->getName());
    }

    public function testImmutability(): void
    {
        $recipient = new Recipient('original@example.com', 'Original Name');

        $withEmail = $recipient->withEmail('new@example.com');
        $withName  = $recipient->withName('New Name');

        // Original should remain unchanged
        self::assertSame('original@example.com', $recipient->getEmail());
        self::assertSame('Original Name', $recipient->getName());

        // New instances should have changes
        self::assertSame('new@example.com', $withEmail->getEmail());
        self::assertSame('New Name', $withName->getName());
    }
}
