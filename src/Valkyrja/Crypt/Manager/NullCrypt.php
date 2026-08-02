<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Crypt\Manager;

use Override;
use SensitiveParameter;
use stdClass;
use Valkyrja\Crypt\Manager\Contract\CryptContract;

class NullCrypt implements CryptContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encrypt(string $message, #[SensitiveParameter] string|null $key = null): string
    {
        return 'encrypted';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encryptArray(array $array, #[SensitiveParameter] string|null $key = null): string
    {
        return '[]';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encryptObject(object $object, #[SensitiveParameter] string|null $key = null): string
    {
        return '{}';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decrypt(string $encrypted, #[SensitiveParameter] string|null $key = null): string
    {
        return 'decrypted';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decryptArray(string $encrypted, #[SensitiveParameter] string|null $key = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decryptObject(string $encrypted, #[SensitiveParameter] string|null $key = null): object
    {
        return new stdClass();
    }
}
