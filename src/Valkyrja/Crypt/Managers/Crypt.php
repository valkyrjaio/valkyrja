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
use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Crypt as Contract;
use Valkyrja\Crypt\Exceptions\CryptException;

use function file_exists;
use function file_get_contents;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt implements Contract
{
    /**
     * The adapters.
     *
     * @var Adapter[]
     */
    protected static array $adapters = [];

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
     * The default adapter.
     *
     * @var string
     */
    protected string $defaultAdapter;

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
        $this->container      = $container;
        $this->config         = $config;
        $this->defaultAdapter = $config['adapter'];
    }

    /**
     * Get an adapter by name.
     *
     * @param string|null $name The adapter name
     *
     * @return Adapter
     */
    public function getAdapter(string $name = null): Adapter
    {
        $name ??= $this->defaultAdapter;

        return self::$adapters[$name]
            ?? self::$adapters[$name] = $this->container->getSingleton(
                $this->config['adapters'][$name]
            );
    }

    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key ?? ($this->key = $this->getKeyFromFilesystem() ?? $this->getKeyFromConfig());
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
        return $this->getAdapter()->isValidEncryptedMessage($encrypted);
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
        return $this->getAdapter()->encrypt($message, $key ?? $this->getKey());
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
        return $this->getAdapter()->decrypt($encrypted, $key ?? $this->getKey());
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
        return $this->getAdapter()->encryptArray($array, $key ?? $this->getKey());
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
        return $this->getAdapter()->decryptArray($encrypted, $key ?? $this->getKey());
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
        return $this->getAdapter()->encryptObject($object, $key ?? $this->getKey());
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
        return $this->getAdapter()->decryptObject($encrypted, $key ?? $this->getKey());
    }

    /**
     * Get the key from config.
     *
     * @return string
     */
    protected function getKeyFromConfig(): string
    {
        return $this->config['key'];
    }

    /**
     * Get the key path from config.
     *
     * @return string|null
     */
    protected function getKeyPathFromConfig(): ?string
    {
        return $this->config['keyPath'];
    }

    /**
     * Get the key from a key path config.
     *
     * @return string|null
     */
    protected function getKeyFromFilesystem(): ?string
    {
        return $this->hasValidKeyPath() ? $this->getFileContentsFromKeyPath() : null;
    }

    /**
     * Check if a valid key path exists in config.
     *
     * @return bool
     */
    protected function hasValidKeyPath(): bool
    {
        return null !== $this->getKeyPathFromConfig() && file_exists($this->getKeyPathFromConfig());
    }

    /**
     * Get file contents from key path.
     *
     * @return string|null
     */
    protected function getFileContentsFromKeyPath(): ?string
    {
        return file_get_contents($this->getKeyPathFromConfig()) ?: null;
    }
}