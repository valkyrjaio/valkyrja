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
use Valkyrja\Jwt\Config\EdDsaConfiguration;

/**
 * Class EdDsaAdapter.
 *
 * @author Melech Mizrachi
 *
 * @property EdDsaConfiguration $config
 */
class EdDsaAdapter extends FirebaseAdapter
{
    /**
     * EdDsaAdapter constructor.
     */
    public function __construct(EdDsaConfiguration $config)
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
            throw new RuntimeException('Invalid private key provided');
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
