<?php

declare(strict_types=1);

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
 * Interface Encrypter.
 *
 * @author Melech Mizrachi
 */
interface Encrypter
{
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
    public function encrypt(string $message, string $key): string;

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
    public function encryptArray(array $array, string $key): string;

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
    public function encryptObject(object $object, string $key): string;
}
