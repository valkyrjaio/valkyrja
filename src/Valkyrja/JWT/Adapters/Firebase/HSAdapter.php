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

namespace Valkyrja\JWT\Adapters\Firebase;

use Valkyrja\JWT\Adapters\FirebaseAdapter;

/**
 * Class HSAdapter.
 *
 * @author Melech Mizrachi
 */
class HSAdapter extends FirebaseAdapter
{
    /**
     * @inheritDoc
     */
    protected function setEncodeKey(): void
    {
        $this->encodeKey = $this->config['key'];
    }

    /**
     * @inheritDoc
     */
    protected function setDecodeKey(): void
    {
        $this->decodeKey = $this->config['key'];
    }
}