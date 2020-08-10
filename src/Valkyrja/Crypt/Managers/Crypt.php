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

use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt as Contract;
use Valkyrja\Crypt\Driver;
use Valkyrja\Crypt\Exceptions\CryptException;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $driversCache = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The adapters.
     *
     * @var array
     */
    protected array $adapters;

    /**
     * The crypts.
     *
     * @var array
     */
    protected array $crypts;

    /**
     * The drivers config.
     *
     * @var array
     */
    protected array $drivers;

    /**
     * The default crypt.
     *
     * @var string
     */
    protected string $defaultCrypt;

    /**
     * The key
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Crypt constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container     = $container;
        $this->config        = $config;
        $this->crypts        = $config['crypts'];
        $this->adapters      = $config['drivers'];
        $this->drivers = $config['drivers'];
        $this->defaultCrypt  = $config['default'];
    }

    /**
     * Use a crypt by name.
     *
     * @param string|null $name    The crypt name
     * @param string|null $adapter The adapter
     *b bh
     * @return Driver
     */
    public function useCrypt(string $name = null, string $adapter = null): Driver
    {
        // The session to use
        $name ??= $this->defaultCrypt;
        // The crypt to use
        $crypt = $this->crypts[$name];
        // The adapter to use
        $adapter ??= $crypt['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$crypt['driver']],
                [
                    $name,
                    $this->adapters[$adapter],
                ]
            );
    }

    /**
     * Determine if an encrypted message is valid.
     *
     * @param string $encrypted
     *
     * @return bool
     */
    public function isValidEncryptedMessage(string $encrypted): bool
    {
        return $this->useCrypt()->isValidEncryptedMessage($encrypted);
    }

    /**
     * Encrypt a message.
     *
     * @param string      $message The message to encrypt
     * @param string|null $key     The encryption key
     *
     * @throws CryptException
     *
     * @return string
     */
    public function encrypt(string $message, string $key = null): string
    {
        return $this->useCrypt()->encrypt($message, $key);
    }

    /**
     * Decrypt a message.
     *
     * @param string      $encrypted The encrypted message to decrypt
     * @param string|null $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function decrypt(string $encrypted, string $key = null): string
    {
        return $this->useCrypt()->decrypt($encrypted, $key);
    }

    /**
     * Encrypt an array.
     *
     * @param array       $array The array to encrypt
     * @param string|null $key   The encryption key
     *
     * @throws CryptException
     *
     * @return string
     */
    public function encryptArray(array $array, string $key = null): string
    {
        return $this->useCrypt()->encryptArray($array, $key);
    }

    /**
     * Decrypt a message originally encrypted from an array.
     *
     * @param string      $encrypted The encrypted message
     * @param string|null $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return array
     */
    public function decryptArray(string $encrypted, string $key = null): array
    {
        return $this->useCrypt()->decryptArray($encrypted, $key);
    }

    /**
     * Encrypt a json array.
     *
     * @param object      $object The object to encrypt
     * @param string|null $key    The encryption key
     *
     * @throws CryptException
     *
     * @return string
     */
    public function encryptObject(object $object, string $key = null): string
    {
        return $this->useCrypt()->encryptObject($object, $key);
    }

    /**
     * Decrypt a message originally encrypted from an object.
     *
     * @param string      $encrypted The encrypted message
     * @param string|null $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return object
     */
    public function decryptObject(string $encrypted, string $key = null): object
    {
        return $this->useCrypt()->decryptObject($encrypted, $key);
    }
}
