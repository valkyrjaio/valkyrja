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

namespace Valkyrja\Crypt\Manager;

use JsonException;
use Override;
use Random\RandomException;
use SensitiveParameter;
use SodiumException;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Object\Factory\ObjectFactory;

use function bin2hex;
use function hex2bin;
use function is_string;
use function random_bytes;
use function sodium_crypto_secretbox;
use function sodium_crypto_secretbox_open;
use function sodium_memzero;

use const SODIUM_CRYPTO_SECRETBOX_MACBYTES;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

class SodiumCrypt implements CryptContract
{
    /**
     * @param non-empty-string $key The key
     */
    public function __construct(
        #[SensitiveParameter]
        protected string $key,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        try {
            $this->getDecoded($encrypted);

            return true;
        } catch (CryptException) {
            // Left empty to default to false
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @throws RandomException
     * @throws SodiumException
     */
    #[Override]
    public function encrypt(string $message, #[SensitiveParameter] string|null $key = null): string
    {
        $key    = $this->getKeyAsBytes($key);
        $nonce  = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = bin2hex($nonce . sodium_crypto_secretbox($message, $nonce, $key));

        sodium_memzero($message);
        sodium_memzero($key);

        return $cipher;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     * @throws RandomException
     */
    #[Override]
    public function encryptArray(array $array, #[SensitiveParameter] string|null $key = null): string
    {
        return $this->encrypt(ArrayFactory::toString($array), $key);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     * @throws RandomException
     */
    #[Override]
    public function encryptObject(object $object, #[SensitiveParameter] string|null $key = null): string
    {
        /** @var non-empty-string $objectAsString */
        $objectAsString = ObjectFactory::toString($object);

        return $this->encrypt($objectAsString, $key);
    }

    /**
     * @inheritDoc
     *
     * @throws SodiumException
     */
    #[Override]
    public function decrypt(string $encrypted, #[SensitiveParameter] string|null $key = null): string
    {
        $key   = $this->getKeyAsBytes($key);
        $plain = $this->getDecodedPlain($this->getDecoded($encrypted), $key);

        sodium_memzero($key);

        return $plain;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     */
    #[Override]
    public function decryptArray(string $encrypted, #[SensitiveParameter] string|null $key = null): array
    {
        return ArrayFactory::fromString($this->decrypt($encrypted, $key));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     */
    #[Override]
    public function decryptObject(string $encrypted, #[SensitiveParameter] string|null $key = null): object
    {
        return ObjectFactory::fromString($this->decrypt($encrypted, $key));
    }

    /**
     * Get a decoded encrypted message.
     *
     * @param non-empty-string $encrypted The encrypted message
     *
     * @throws CryptException
     */
    protected function getDecoded(string $encrypted): string
    {
        $decoded = $this->hex2bin($encrypted);

        $this->validateDecoded($decoded);

        /** @var string $decoded Checked in validateDecoded */

        return $decoded;
    }

    /**
     * Validate a decoded encrypted message.
     *
     * @throws CryptException
     */
    protected function validateDecoded(string|false $decoded): void
    {
        $this->validateDecodedType($decoded);
        /** @var string $decoded */
        $this->validateDecodedStrLen($decoded);
    }

    /**
     * Validate a decoded encrypted message type.
     *
     * @throws CryptException
     */
    protected function validateDecodedType(string|false $decoded): void
    {
        if (! $this->isValidDecodedType($decoded)) {
            throw new CryptException('The encoding failed');
        }
    }

    /**
     * Check if a decoded encrypted message is a valid type.
     */
    protected function isValidDecodedType(string|false $decoded): bool
    {
        return $decoded !== false;
    }

    /**
     * Validate a decoded encrypted message string length.
     *
     * @throws CryptException
     */
    protected function validateDecodedStrLen(string $decoded): void
    {
        if (! $this->isValidDecodedStrLen($decoded)) {
            throw new CryptException('The message was truncated');
        }
    }

    /**
     * Validate a decoded encrypted message string length.
     */
    protected function isValidDecodedStrLen(string $decoded): bool
    {
        return mb_strlen($decoded, '8bit') > (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES);
    }

    /**
     * Get plain text from decoded encrypted string.
     *
     * @param string           $decoded The decoded encrypted message
     * @param non-empty-string $key     The encryption key
     *
     * @throws CryptException
     * @throws SodiumException
     *
     * @return non-empty-string
     */
    protected function getDecodedPlain(string $decoded, #[SensitiveParameter] string $key): string
    {
        $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $cipherText = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = $this->sodiumCryptoSecretboxOpen($cipherText, $nonce, $key);

        $this->validatePlainDecoded($plain);

        sodium_memzero($cipherText);

        return $plain;
    }

    /**
     * @param string           $cipherText The cipher text
     * @param string           $nonce      The nonce
     * @param non-empty-string $key        The encryption key
     *
     * @throws SodiumException
     *
     * @return non-empty-string|false
     */
    protected function sodiumCryptoSecretboxOpen(string $cipherText, string $nonce, #[SensitiveParameter] string $key): string|false
    {
        /** @var non-empty-string|false $plain */
        $plain = sodium_crypto_secretbox_open($cipherText, $nonce, $key);

        return $plain;
    }

    /**
     * Validate a plain text encrypted message.
     *
     * @psalm-assert non-empty-string $plain
     *
     * @throws CryptException
     */
    protected function validatePlainDecoded(string|bool $plain): void
    {
        if (! $this->isValidPlainDecoded($plain)) {
            throw new CryptException('The message was tampered with in transit');
        }
    }

    /**
     * Validate a plain text encrypted message.
     *
     * @psalm-assert non-empty-string $plain
     */
    protected function isValidPlainDecoded(string|bool $plain): bool
    {
        return is_string($plain) && $plain !== '';
    }

    /**
     * Get a key as bytes.
     *
     * @param non-empty-string|null $key [optional] The key
     *
     * @return non-empty-string
     */
    protected function getKeyAsBytes(#[SensitiveParameter] string|null $key = null): string
    {
        $key ??= $this->key;

        $keyAsBytes = $this->hex2bin($key);

        if ($keyAsBytes === false || $keyAsBytes === '') {
            throw new CryptException("$key could not be converted to bytes");
        }

        return $keyAsBytes;
    }

    /**
     * @param non-empty-string $key The key
     *
     * @return string|false
     */
    protected function hex2bin(string $key): string|false
    {
        return hex2bin($key);
    }
}
