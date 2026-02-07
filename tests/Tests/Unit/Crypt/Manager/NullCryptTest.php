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

namespace Valkyrja\Tests\Unit\Crypt\Manager;

use stdClass;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\NullCrypt;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NullCryptTest extends TestCase
{
    protected NullCrypt $crypt;

    protected function setUp(): void
    {
        $this->crypt = new NullCrypt();
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(CryptContract::class, $this->crypt);
    }

    public function testIsValidEncryptedMessageAlwaysReturnsTrue(): void
    {
        self::assertTrue($this->crypt->isValidEncryptedMessage('any-string'));
        self::assertTrue($this->crypt->isValidEncryptedMessage(''));
    }

    public function testEncryptReturnsEmptyString(): void
    {
        self::assertSame('encrypted', $this->crypt->encrypt('test message'));
        self::assertSame('encrypted', $this->crypt->encrypt('test message', 'key'));
    }

    public function testEncryptArrayReturnsEmptyString(): void
    {
        self::assertSame('[]', $this->crypt->encryptArray(['key' => 'value']));
        self::assertSame('[]', $this->crypt->encryptArray(['key' => 'value'], 'key'));
    }

    public function testEncryptObjectReturnsEmptyString(): void
    {
        $object = new stdClass();

        self::assertSame('{}', $this->crypt->encryptObject($object));
        self::assertSame('{}', $this->crypt->encryptObject($object, 'key'));
    }

    public function testDecryptReturnsEmptyString(): void
    {
        self::assertSame('decrypted', $this->crypt->decrypt('encrypted'));
        self::assertSame('decrypted', $this->crypt->decrypt('encrypted', 'key'));
    }

    public function testDecryptArrayReturnsEmptyArray(): void
    {
        self::assertSame([], $this->crypt->decryptArray('encrypted'));
        self::assertSame([], $this->crypt->decryptArray('encrypted', 'key'));
    }

    public function testDecryptObjectReturnsStdClass(): void
    {
        $result = $this->crypt->decryptObject('encrypted');

        self::assertInstanceOf(stdClass::class, $result);
    }

    public function testDecryptObjectWithKeyReturnsStdClass(): void
    {
        $result = $this->crypt->decryptObject('encrypted', 'key');

        self::assertInstanceOf(stdClass::class, $result);
    }
}
