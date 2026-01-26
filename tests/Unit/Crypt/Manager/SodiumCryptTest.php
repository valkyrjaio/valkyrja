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

use JsonException;
use Random\RandomException;
use SodiumException;
use stdClass;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Manager\SodiumCrypt;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function bin2hex;
use function random_bytes;

use const SODIUM_CRYPTO_SECRETBOX_KEYBYTES;

class SodiumCryptTest extends TestCase
{
    protected SodiumCrypt $crypt;

    /** @var non-empty-string */
    protected string $key;

    /**
     * @throws RandomException
     */
    protected function setUp(): void
    {
        // Generate a valid 32-byte key in hex format (64 hex characters)
        $this->key   = bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
        $this->crypt = new SodiumCrypt($this->key);
    }

    public function testInstanceOfContract(): void
    {
        self::assertInstanceOf(CryptContract::class, $this->crypt);
    }

    /**
     * @throws CryptException
     * @throws RandomException
     * @throws SodiumException
     */
    public function testEncryptAndDecrypt(): void
    {
        $message   = 'Test message to encrypt';
        $encrypted = $this->crypt->encrypt($message);

        self::assertNotSame($message, $encrypted);
        self::assertNotEmpty($encrypted);

        $decrypted = $this->crypt->decrypt($encrypted);

        self::assertSame($message, $decrypted);
    }

    /**
     * @throws CryptException
     * @throws JsonException
     * @throws RandomException
     * @throws SodiumException
     */
    public function testEncryptArrayAndDecryptArray(): void
    {
        $array     = ['key' => 'value', 'nested' => ['a' => 1]];
        $encrypted = $this->crypt->encryptArray($array);

        self::assertNotEmpty($encrypted);

        $decrypted = $this->crypt->decryptArray($encrypted);

        self::assertSame($array, $decrypted);
    }

    /**
     * @throws CryptException
     * @throws JsonException
     * @throws RandomException
     * @throws SodiumException
     */
    public function testEncryptObjectAndDecryptObject(): void
    {
        $object       = new stdClass();
        $object->name = 'Test';
        $object->id   = 123;

        $encrypted = $this->crypt->encryptObject($object);

        self::assertNotEmpty($encrypted);

        $decrypted = $this->crypt->decryptObject($encrypted);

        self::assertInstanceOf(stdClass::class, $decrypted);
        self::assertSame('Test', $decrypted->name);
        self::assertSame(123, $decrypted->id);
    }

    /**
     * @throws CryptException
     * @throws RandomException
     * @throws SodiumException
     */
    public function testIsValidEncryptedMessageWithValidMessage(): void
    {
        $encrypted = $this->crypt->encrypt('test');

        self::assertTrue($this->crypt->isValidEncryptedMessage($encrypted));
    }

    public function testIsValidEncryptedMessageWithInvalidMessage(): void
    {
        self::assertFalse(@$this->crypt->isValidEncryptedMessage('invalid'));
        self::assertFalse(@$this->crypt->isValidEncryptedMessage(''));
    }

    /**
     * @throws CryptException
     * @throws SodiumException
     */
    public function testDecryptWithInvalidMessageThrowsException(): void
    {
        $this->expectException(CryptException::class);

        @$this->crypt->decrypt('invalid-encrypted-message');
    }

    /**
     * @throws CryptException
     * @throws RandomException
     * @throws SodiumException
     */
    public function testEncryptWithCustomKey(): void
    {
        $customKey = bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
        $message   = 'Test with custom key';

        $encrypted = $this->crypt->encrypt($message, $customKey);

        self::assertNotEmpty($encrypted);

        $decrypted = $this->crypt->decrypt($encrypted, $customKey);

        self::assertSame($message, $decrypted);
    }
}
