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

namespace Valkyrja\Crypt\Crypts;

use Exception;
use Valkyrja\Application\Application;
use Valkyrja\Crypt\Crypt as CryptContract;
use Valkyrja\Crypt\Decrypter;
use Valkyrja\Crypt\Encrypter;
use Valkyrja\Crypt\Exceptions\CryptException;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 */
class Crypt implements CryptContract
{
    use Provides;

    /**
     * The encrypter.
     *
     * @var Encrypter
     */
    protected Encrypter $encrypter;

    /**
     * The decrypter.
     *
     * @var Decrypter
     */
    protected Decrypter $decrypter;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The key
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * SodiumCrypt constructor.
     *
     * @param Application $app
     * @param Encrypter   $encrypter
     * @param Decrypter   $decrypter
     */
    public function __construct(Application $app, Encrypter $encrypter, Decrypter $decrypter)
    {
        $this->config    = (array) $app->config()['crypt'];
        $this->encrypter = $encrypter;
        $this->decrypter = $decrypter;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CryptContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $container = $app->container();

        $container->setSingleton(
            CryptContract::class,
            new static(
                $app,
                $container->get(Encrypter::class),
                $container->get(Decrypter::class)
            )
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
        return $this->decrypter->isValidEncryptedMessage($encrypted);
    }

    /**
     * Encrypt a message.
     *
     * @param string $message The message to encrypt
     * @param string $key     The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encrypt(string $message, string $key = null): string
    {
        return $this->encrypter->encrypt($message, $key ?? $this->getKey());
    }

    /**
     * Decrypt a message.
     *
     * @param string $encrypted The encrypted message to decrypt
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return string
     */
    public function decrypt(string $encrypted, string $key = null): string
    {
        return $this->decrypter->decrypt($encrypted, $key ?? $this->getKey());
    }

    /**
     * Encrypt an array.
     *
     * @param array  $array The array to encrypt
     * @param string $key   The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encryptArray(array $array, string $key = null): string
    {
        return $this->encrypter->encryptArray($array, $key ?? $this->getKey());
    }

    /**
     * Decrypt a message originally encrypted from an array.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return array
     */
    public function decryptArray(string $encrypted, string $key = null): array
    {
        return $this->decrypter->decryptArray($encrypted, $key ?? $this->getKey());
    }

    /**
     * Encrypt a json array.
     *
     * @param object $object The object to encrypt
     * @param string $key    The encryption key
     *
     * @throws Exception Random Bytes Failure
     *
     * @return string
     */
    public function encryptObject(object $object, string $key = null): string
    {
        return $this->encrypter->encryptObject($object, $key ?? $this->getKey());
    }

    /**
     * Decrypt a message originally encrypted from an object.
     *
     * @param string $encrypted The encrypted message
     * @param string $key       The encryption key
     *
     * @throws CryptException On any failure
     *
     * @return object
     */
    public function decryptObject(string $encrypted, string $key = null): object
    {
        return $this->decrypter->decryptObject($encrypted, $key ?? $this->getKey());
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
