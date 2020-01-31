<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Crypt;

use Valkyrja\Crypt\Exceptions\CryptException;

/**
 * Interface Crypt.
 *
 * @author Melech Mizrachi
 */
interface Crypt
{
    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Encrypt a message.
     *
     * @param string $message The message to encrypt
     * @param string $key     The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function encrypt(string $message, string $key = null): string;

    /**
     * Decrypt a message.
     *
     * @param string $encrypted The encrypted message to decrypt
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function decrypt(string $encrypted, string $key = null): string;

    /**
     * Encrypt an array.
     *
     * @param array  $array The array to encrypt
     * @param string $key   The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function encryptArray(array $array, string $key = null): string;

    /**
     * Decrypt a message originally encrypted from an array.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return array
     */
    public function decryptArray(string $encrypted, string $key = null): array;

    /**
     * Encrypt a json array.
     *
     * @param object $object The object to encrypt
     * @param string $key    The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function encryptObject(object $object, string $key = null): string;

    /**
     * Decrypt a message originally encrypted from an object.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return object
     */
    public function decryptObject(string $encrypted, string $key = null): object;
}
