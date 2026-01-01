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
use SodiumException;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\BuiltIn\Support\Obj;

use function bin2hex;
use function hex2bin;
use function random_bytes;
use function sodium_crypto_secretbox;
use function sodium_crypto_secretbox_open;
use function sodium_memzero;

use const SODIUM_CRYPTO_SECRETBOX_MACBYTES;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

/**
 * Class SodiumCrypt.
 */
class SodiumCrypt implements CryptContract
{
    /**
     * @param non-empty-string $key The key
     */
    public function __construct(
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
    public function encrypt(string $message, string|null $key = null): string
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
     */
    #[Override]
    public function encryptArray(array $array, string|null $key = null): string
    {
        return $this->encrypt(Arr::toString($array), $key);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     */
    #[Override]
    public function encryptObject(object $object, string|null $key = null): string
    {
        return $this->encrypt(Obj::toString($object), $key);
    }

    /**
     * @inheritDoc
     *
     * @throws SodiumException
     */
    #[Override]
    public function decrypt(string $encrypted, string|null $key = null): string
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
    public function decryptArray(string $encrypted, string|null $key = null): array
    {
        return Arr::fromString($this->decrypt($encrypted, $key));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws SodiumException
     */
    #[Override]
    public function decryptObject(string $encrypted, string|null $key = null): object
    {
        return Obj::fromString($this->decrypt($encrypted, $key));
    }

    /**
     * Get a decoded encrypted message.
     *
     * @param string $encrypted The encrypted message
     *
     * @throws CryptException
     *
     * @return string
     */
    protected function getDecoded(string $encrypted): string
    {
        $decoded = hex2bin($encrypted);

        $this->validateDecoded($decoded);

        /** @var string $decoded Checked in validateDecoded */

        return $decoded;
    }

    /**
     * Validate a decoded encrypted message.
     *
     * @param string|false $decoded
     *
     * @throws CryptException
     *
     * @return void
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
     * @param string|false $decoded
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validateDecodedType(string|false $decoded): void
    {
        if (! $this->isValidDecodedType($decoded)) {
            throw new CryptException('The encoding failed');
        }
    }

    /**
     * Check if a decoded encrypted message is a valid type.
     *
     * @param string|false $decoded
     *
     * @return bool
     */
    protected function isValidDecodedType(string|false $decoded): bool
    {
        return $decoded !== false;
    }

    /**
     * Validate a decoded encrypted message string length.
     *
     * @param string $decoded
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validateDecodedStrLen(string $decoded): void
    {
        if (! $this->isValidDecodedStrLen($decoded)) {
            throw new CryptException('The message was truncated');
        }
    }

    /**
     * Validate a decoded encrypted message string length.
     *
     * @param string $decoded
     *
     * @return bool
     */
    protected function isValidDecodedStrLen(string $decoded): bool
    {
        return mb_strlen($decoded, '8bit') > (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES);
    }

    /**
     * Get plain text from decoded encrypted string.
     *
     * @param string      $decoded The decoded encrypted message
     * @param string|null $key     The encryption key
     *
     * @throws CryptException
     * @throws SodiumException
     *
     * @return string
     */
    protected function getDecodedPlain(string $decoded, string|null $key = null): string
    {
        if ($key === null) {
            throw new CryptException("Invalid ky `$key` provided");
        }

        $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $cipherText = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = sodium_crypto_secretbox_open($cipherText, $nonce, $key);

        $this->validatePlainDecoded($plain);

        /** @var string $plain */
        sodium_memzero($cipherText);

        return $plain;
    }

    /**
     * Validate a plain text encrypted message.
     *
     * @param bool|string $plain
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validatePlainDecoded(bool|string $plain): void
    {
        if (! $this->isValidPlainDecoded($plain)) {
            throw new CryptException('The message was tampered with in transit');
        }
    }

    /**
     * Validate a plain text encrypted message.
     *
     * @param bool|string $plain
     *
     * @return bool
     */
    protected function isValidPlainDecoded(bool|string $plain): bool
    {
        return $plain !== false;
    }

    /**
     * Get a key as bytes.
     *
     * @param string|null $key [optional] The key
     *
     * @return string
     */
    protected function getKeyAsBytes(string|null $key = null): string
    {
        $key ??= $this->key;

        $keyAsBytes = hex2bin($key);

        if ($keyAsBytes === false) {
            throw new CryptException("$key could not be converted to bytes");
        }

        return $keyAsBytes;
    }
}
