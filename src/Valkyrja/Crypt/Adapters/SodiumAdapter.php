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

namespace Valkyrja\Crypt\Adapters;

use Exception;
use JsonException;
use SodiumException;
use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

use function base64_decode;
use function base64_encode;
use function is_string;
use function json_decode;
use function random_bytes;
use function sodium_crypto_secretbox;
use function sodium_crypto_secretbox_open;
use function sodium_memzero;

use const JSON_THROW_ON_ERROR;
use const SODIUM_CRYPTO_SECRETBOX_MACBYTES;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

/**
 * Class SodiumAdapter.
 *
 * @author Melech Mizrachi
 */
class SodiumAdapter implements Adapter
{
    /**
     * Determine if an encrypted message is valid.
     *
     * @param string $encrypted
     *
     * @return bool
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        try {
            $this->getDecoded($encrypted);

            return true;
        } catch (CryptException $exception) {
            // Left empty to default to false
        }

        return false;
    }

    /**
     * Encrypt a message.
     *
     * @param string $message The message to encrypt
     * @param string $key     The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encrypt(string $message, string $key): string
    {
        $nonce  = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = base64_encode($nonce . sodium_crypto_secretbox($message, $nonce, $key));

        sodium_memzero($message);
        sodium_memzero($key);

        return $cipher;
    }

    /**
     * Encrypt an array.
     *
     * @param array  $array The array to encrypt
     * @param string $key   The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encryptArray(array $array, string $key): string
    {
        return $this->encrypt(Arr::toString($array), $key);
    }

    /**
     * Encrypt a json array.
     *
     * @param object $object The object to encrypt
     * @param string $key    The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encryptObject(object $object, string $key): string
    {
        return $this->encrypt(Obj::toString($object), $key);
    }

    /**
     * Decrypt a message.
     *
     * @param string $encrypted The encrypted message to decrypt
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     * @throws SodiumException
     *
     * @return string
     */
    public function decrypt(string $encrypted, string $key): string
    {
        $plain = $this->getDecodedPlain($this->getDecoded($encrypted), $key);

        sodium_memzero($key);

        return $plain;
    }

    /**
     * Decrypt a message originally encrypted from an array.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     * @throws JsonException
     * @throws SodiumException
     *
     * @return array
     */
    public function decryptArray(string $encrypted, string $key): array
    {
        return json_decode($this->decrypt($encrypted, $key), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Decrypt a message originally encrypted from an object.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     * @throws JsonException
     * @throws SodiumException
     *
     * @return object
     */
    public function decryptObject(string $encrypted, string $key): object
    {
        return json_decode($this->decrypt($encrypted, $key), false, 512, JSON_THROW_ON_ERROR);
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
        $decoded = base64_decode($encrypted, true);

        $this->validateDecoded($decoded);

        return $decoded;
    }

    /**
     * Validate a decoded encrypted message.
     *
     * @param bool|string $decoded
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validateDecoded($decoded): void
    {
        $this->validateDecodedType($decoded);
        $this->validateDecodedStrLen($decoded);
    }

    /**
     * Validate a decoded encrypted message type.
     *
     * @param bool|string $decoded
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validateDecodedType($decoded): void
    {
        if (! $this->isValidDecodedType($decoded)) {
            throw new CryptException('The encoding failed');
        }
    }

    /**
     * Check if a decoded encrypted message is a valid type.
     *
     * @param bool|string $decoded
     *
     * @return bool
     */
    protected function isValidDecodedType($decoded): bool
    {
        return $decoded !== false && is_string($decoded);
    }

    /**
     * Validate a decoded encrypted message string length.
     *
     * @param bool|string $decoded
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function validateDecodedStrLen($decoded): void
    {
        if (! $this->isValidDecodedStrLen($decoded)) {
            throw new CryptException('The message was truncated');
        }
    }

    /**
     * Validate a decoded encrypted message string length.
     *
     * @param bool|string $decoded
     *
     * @return bool
     */
    protected function isValidDecodedStrLen($decoded): bool
    {
        return mb_strlen($decoded, '8bit') > (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES);
    }

    /**
     * Get plain text from decoded encrypted string.
     *
     * @param string $decoded The decoded encrypted message
     * @param string $key     The encryption key
     *
     * @throws CryptException
     * @throws SodiumException
     *
     * @return string
     */
    protected function getDecodedPlain(string $decoded, string $key): string
    {
        $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $cipherText = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = sodium_crypto_secretbox_open($cipherText, $nonce, $key);

        $this->validatePlainDecoded($plain);

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
    protected function validatePlainDecoded($plain): void
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
    protected function isValidPlainDecoded($plain): bool
    {
        return $plain !== false;
    }
}