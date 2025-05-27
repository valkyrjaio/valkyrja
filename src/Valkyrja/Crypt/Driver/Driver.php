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

namespace Valkyrja\Crypt\Driver;

use Valkyrja\Crypt\Adapter\Contract\Adapter;
use Valkyrja\Crypt\Driver\Contract\Driver as Contract;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * Driver constructor.
     */
    public function __construct(
        protected Adapter $adapter
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return $this->adapter->isValidEncryptedMessage($encrypted);
    }

    /**
     * @inheritDoc
     */
    public function encrypt(string $message, string|null $key = null): string
    {
        return $this->adapter->encrypt($message, $key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string|null $key = null): string
    {
        return $this->adapter->decrypt($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptArray(array $array, string|null $key = null): string
    {
        return $this->adapter->encryptArray($array, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptArray(string $encrypted, string|null $key = null): array
    {
        return $this->adapter->decryptArray($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptObject(object $object, string|null $key = null): string
    {
        return $this->adapter->encryptObject($object, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptObject(string $encrypted, string|null $key = null): object
    {
        return $this->adapter->decryptObject($encrypted, $key);
    }
}
