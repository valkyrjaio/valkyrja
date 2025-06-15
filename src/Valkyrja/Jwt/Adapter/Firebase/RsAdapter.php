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

use RuntimeException;
use Valkyrja\Jwt\Adapter\FirebaseAdapter;
use Valkyrja\Jwt\Config\RsConfiguration;
use Valkyrja\Jwt\Support\KeyGen;

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

        $this->encodeKey = $encodeKey;
    }

    /**
     * @inheritDoc
     */
    protected function setDecodeKey(): void
    {
        $decodeKey = $this->config->publicKey;

        if ($decodeKey === '') {
            throw new RuntimeException('Invalid public key provided');
        }

        $this->decodeKey = $decodeKey;
    }
}
