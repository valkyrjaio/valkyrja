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

use function is_string;

/**
 * Class HsAdapter.
 *
 * @author Melech Mizrachi
 */
class HsAdapter extends FirebaseAdapter
{
    /**
     * @inheritDoc
     */
    protected function setEncodeKey(): void
    {
        $encodeKey = $this->config['key'];

        if (! is_string($encodeKey)) {
            throw new RuntimeException('Invalid private key provided');
        }

        $this->encodeKey = $encodeKey;
    }

    /**
     * @inheritDoc
     */
    protected function setDecodeKey(): void
    {
        $decodeKey = $this->config['key'];

        if (! is_string($decodeKey)) {
            throw new RuntimeException('Invalid public key provided');
        }

        $this->decodeKey = $decodeKey;
    }
}
