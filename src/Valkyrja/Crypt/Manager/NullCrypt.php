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
