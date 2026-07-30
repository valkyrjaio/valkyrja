<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Crypt\Manager\Contract;

use SensitiveParameter;

interface CryptContract
{
    /**
     * Determine if an encrypted message is valid.
     *
     * @param non-empty-string $encrypted The encrypted message
     */
    public function isValidEncryptedMessage(string $encrypted): bool;

    /**
     * Encrypt a message.
     *
     * @param non-empty-string      $message The message to encrypt
     * @param non-empty-string|null $key     The encryption key
     *
     * @return non-empty-string
     */
    public function encrypt(string $message, #[SensitiveParameter] string|null $key = null): string;

    /**
     * Decrypt a message.
     *
     * @param non-empty-string      $encrypted The encrypted message to decrypt
     * @param non-empty-string|null $key       The encryption key
     *
     * @return non-empty-string
     */
    public function decrypt(string $encrypted, #[SensitiveParameter] string|null $key = null): string;

    /**
     * Encrypt an array.
     *
     * @param array<array-key, mixed> $array The array to encrypt
     * @param non-empty-string|null   $key   The encryption key
     *
     * @return non-empty-string
     */
    public function encryptArray(array $array, #[SensitiveParameter] string|null $key = null): string;

    /**
     * Decrypt a message originally encrypted from an array.
     *
     * @param non-empty-string      $encrypted The encrypted message
     * @param non-empty-string|null $key       The encryption key
     *
     * @return array<array-key, mixed>
     */
    public function decryptArray(string $encrypted, #[SensitiveParameter] string|null $key = null): array;

    /**
     * Encrypt a json array.
     *
     * @param object                $object The object to encrypt
     * @param non-empty-string|null $key    The encryption key
     *
     * @return non-empty-string
     */
    public function encryptObject(object $object, #[SensitiveParameter] string|null $key = null): string;

    /**
     * Decrypt a message originally encrypted from an object.
     *
     * @param non-empty-string      $encrypted The encrypted message
     * @param non-empty-string|null $key       The encryption key
     */
    public function decryptObject(string $encrypted, #[SensitiveParameter] string|null $key = null): object;
}
