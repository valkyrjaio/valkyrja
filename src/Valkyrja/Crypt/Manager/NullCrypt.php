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

use Override;
use stdClass;
use Valkyrja\Crypt\Manager\Contract\CryptContract;

/**
 * Class NullCrypt.
 */
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
    public function encrypt(string $message, string|null $key = null): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encryptArray(array $array, string|null $key = null): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function encryptObject(object $object, string|null $key = null): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decrypt(string $encrypted, string|null $key = null): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decryptArray(string $encrypted, string|null $key = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function decryptObject(string $encrypted, string|null $key = null): object
    {
        return new stdClass();
    }
}
