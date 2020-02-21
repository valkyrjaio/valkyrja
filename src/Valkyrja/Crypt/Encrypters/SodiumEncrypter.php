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

namespace Valkyrja\Crypt\Encrypters;

use Exception;
use const JSON_THROW_ON_ERROR;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
use Valkyrja\Application\Application;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Crypt\Encrypter;
use Valkyrja\Support\Providers\Provides;

/**
 * Class SodiumEncrypter.
 *
 * @author Melech Mizrachi
 */
class SodiumEncrypter implements Encrypter
{
    use Provides;

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::ENCRYPTER,
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
        $app->container()->singleton(Contract::ENCRYPTER, new static());
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
    public function encrypt(string $message, string $key): string
    {
        $nonce  = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = base64_encode($nonce . sodium_crypto_secretbox($message, $nonce, $key));

        sodium_memzero($message);
        sodium_memzero($key);

        return $cipher;
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
    public function encryptArray(array $array, string $key): string
    {
        return $this->encrypt(json_encode($array, JSON_THROW_ON_ERROR), $key);
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
    public function encryptObject(object $object, string $key): string
    {
        return $this->encrypt(json_encode($object, JSON_THROW_ON_ERROR), $key);
    }
}
