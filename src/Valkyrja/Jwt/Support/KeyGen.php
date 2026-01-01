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

namespace Valkyrja\Jwt\Support;

use OpenSSLAsymmetricKey;
use SodiumException;
use Valkyrja\Jwt\Data\EdDsaKey;
use Valkyrja\Throwable\Exception\RuntimeException;

use function file_get_contents;

class KeyGen
{
    /**
     * Get a public/private key pair to use for EdDSA.
     *
     * @throws SodiumException
     *
     * @return EdDsaKey
     */
    public static function edDsa(): EdDsaKey
    {
        $keyPair = sodium_crypto_sign_keypair();

        $key = new EdDsaKey();

        $key->privateKey = base64_encode(sodium_crypto_sign_secretkey($keyPair));
        $key->publicKey  = base64_encode(sodium_crypto_sign_publickey($keyPair));

        return $key;
    }

    /**
     * Get an openssl private key via a file path and passphrase.
     *
     * @param string $privateKeyFile The private key file path
     * @param string $passphrase     The passphrase
     *
     * @return OpenSSLAsymmetricKey
     */
    public static function opensslPrivateKey(string $privateKeyFile, string $passphrase): OpenSSLAsymmetricKey
    {
        $privateKeyFileContents = file_get_contents($privateKeyFile);

        if ($privateKeyFileContents === false) {
            throw new RuntimeException("Failed to get contents of `$privateKeyFile`");
        }

        $privateKey = openssl_pkey_get_private(
            $privateKeyFileContents,
            $passphrase
        );

        if ($privateKey === false) {
            throw new RuntimeException("Failed to get private key for private key file `$privateKeyFile` with passphrase `$passphrase`");
        }

        return $privateKey;
    }

    /**
     * Get an openssl private key via a file path and passphrase.
     *
     * @param OpenSSLAsymmetricKey $privateKey The private key
     *
     * @return OpenSSLAsymmetricKey
     */
    public static function opensslPublicKey(OpenSSLAsymmetricKey $privateKey): OpenSSLAsymmetricKey
    {
        $details = openssl_pkey_get_details($privateKey);

        if ($details === false || ! $details['key'] instanceof OpenSSLAsymmetricKey) {
            throw new RuntimeException('Failed to get details from private key');
        }

        return $details['key'];
    }
}
