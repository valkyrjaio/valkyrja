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

namespace Valkyrja\Sms\Data;

class SmsVonageConfig
{
    /**
     * @param string $key    The Vonage api key
     * @param string $secret The Vonage api secret
     */
    public function __construct(
        public readonly string $key = 'vonage-key',
        public readonly string $secret = 'vonage-secret',
    ) {
    }
}
