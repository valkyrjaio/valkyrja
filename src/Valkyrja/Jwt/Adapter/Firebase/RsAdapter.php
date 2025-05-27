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

namespace Valkyrja\Jwt\Adapter\Firebase;

use OpenSSLAsymmetricKey;
use RuntimeException;
use Valkyrja\Jwt\Adapter\FirebaseAdapter;
use Valkyrja\Jwt\Config\RsConfiguration;
use Valkyrja\Jwt\Support\KeyGen;

use function is_string;

/**
 * Class RsAdapter.
 *
 * @author Melech Mizrachi
 *
 * @property RsConfiguration $config
 */
class RsAdapter extends FirebaseAdapter
{
    /**
     * RsAdapter constructor.
     */
    public function __construct(RsConfiguration $config)
    {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    protected function setEncodeKey(): void
    {
        $encodeKey = $this->config->privateKey;

        if ($encodeKey === '') {
            $keyPath    = $this->config->keyPath;
            $passphrase = $this->config->passphrase;

            if ($keyPath === '') {
                throw new RuntimeException('Invalid key path provided');
            }

            if ($passphrase === '') {
                throw new RuntimeException('Invalid passphrase provided');
            }

            $encodeKey = KeyGen::opensslPrivateKey($keyPath, $passphrase);
        }

        if (! is_string($encodeKey) && ! $encodeKey instanceof OpenSSLAsymmetricKey) {
            throw new RuntimeException('Invalid private key provided');
        }

        $this->encodeKey = $encodeKey;
    }

    /**
     * @inheritDoc
     */
    protected function setDecodeKey(): void
    {
        $decodeKey = $this->config['publicKey'] ?? null;

        if ($decodeKey === null) {
            $encodeKey = $this->encodeKey;

            if (! $encodeKey instanceof OpenSSLAsymmetricKey) {
                throw new RuntimeException('When using KeyGen you must use a keyPath and passphrase');
            }
        }

        if (! is_string($decodeKey) && ! $decodeKey instanceof OpenSSLAsymmetricKey) {
            throw new RuntimeException('Invalid public key provided');
        }

        $this->decodeKey = $decodeKey;
    }
}
