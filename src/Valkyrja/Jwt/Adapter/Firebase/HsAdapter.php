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
use Valkyrja\Jwt\Config\HsConfiguration;

/**
 * Class HsAdapter.
 *
 * @author Melech Mizrachi
 *
 * @property HsConfiguration $config
 */
class HsAdapter extends FirebaseAdapter
{
    /**
     * HsAdapter constructor.
     */
    public function __construct(HsConfiguration $config)
    {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    protected function setEncodeKey(): void
    {
        $encodeKey = $this->config->key;

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
        $decodeKey = $this->config->key;

        if ($decodeKey === '') {
            throw new RuntimeException('Invalid public key provided');
        }

        $this->decodeKey = $decodeKey;
    }
}
