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
use Valkyrja\Jwt\Support\KeyGen;

/**
 * Class RsAdapter.
 *
 * @author Melech Mizrachi
 */
class RsAdapter extends FirebaseAdapter
{
    /**
     * @inheritDoc
     */
    protected function setEncodeKey(): void
    {
        $this->encodeKey = $this->config['privateKey']
            ?? KeyGen::opensslPrivateKey($this->config['keyPath'], $this->config['passphrase']);
    }

    /**
     * @inheritDoc
     */
    protected function setDecodeKey(): void
    {
        $this->decodeKey = $this->config['publicKey']
            ?? (($encodeKey = $this->encodeKey) instanceof OpenSSLAsymmetricKey
                ? KeyGen::opensslPublicKey($encodeKey)
                : throw new RuntimeException('When using KeyGen you must use a keyPath and passphrase'));
    }
}
