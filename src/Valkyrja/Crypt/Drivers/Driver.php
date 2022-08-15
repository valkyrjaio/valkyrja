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

namespace Valkyrja\Crypt\Drivers;

use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Driver as Contract;
use Valkyrja\Support\Manager\Drivers\Driver as ParentDriver;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
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
    public function encrypt(string $message, string $key = null): string
    {
        return $this->adapter->encrypt($message, $key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string $key = null): string
    {
        return $this->adapter->decrypt($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptArray(array $array, string $key = null): string
    {
        return $this->adapter->encryptArray($array, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptArray(string $encrypted, string $key = null): array
    {
        return $this->adapter->decryptArray($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptObject(object $object, string $key = null): string
    {
        return $this->adapter->encryptObject($object, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptObject(string $encrypted, string $key = null): object
    {
        return $this->adapter->decryptObject($encrypted, $key);
    }
}
