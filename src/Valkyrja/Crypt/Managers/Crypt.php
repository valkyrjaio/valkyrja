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

namespace Valkyrja\Crypt\Managers;

use Valkyrja\Crypt\Config\Config;
use Valkyrja\Crypt\Crypt as Contract;
use Valkyrja\Crypt\Driver;
use Valkyrja\Crypt\Factory;
use Valkyrja\Support\Manager\Managers\Manager;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 *
 * @property Factory $factory
 */
class Crypt extends Manager implements Contract
{
    /**
     * Crypt constructor.
     *
     * @param Factory      $factory The factory
     * @param Config|array $config  The config
     */
    public function __construct(Factory $factory, Config|array $config)
    {
        parent::__construct($factory, $config);

        $this->configurations = $config['crypts'];
    }

    /**
     * @inheritDoc
     */
    public function use(string $name = null): Driver
    {
        return parent::use($name);
    }

    /**
     * @inheritDoc
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return $this->use()->isValidEncryptedMessage($encrypted);
    }

    /**
     * @inheritDoc
     */
    public function encrypt(string $message, string $key = null): string
    {
        return $this->use()->encrypt($message, $key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt(string $encrypted, string $key = null): string
    {
        return $this->use()->decrypt($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptArray(array $array, string $key = null): string
    {
        return $this->use()->encryptArray($array, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptArray(string $encrypted, string $key = null): array
    {
        return $this->use()->decryptArray($encrypted, $key);
    }

    /**
     * @inheritDoc
     */
    public function encryptObject(object $object, string $key = null): string
    {
        return $this->use()->encryptObject($object, $key);
    }

    /**
     * @inheritDoc
     */
    public function decryptObject(string $encrypted, string $key = null): object
    {
        return $this->use()->decryptObject($encrypted, $key);
    }
}
