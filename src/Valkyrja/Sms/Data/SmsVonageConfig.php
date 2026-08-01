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

use Valkyrja\Sms\Data\Contract\SmsVonageConfigContract;

class SmsVonageConfig implements SmsVonageConfigContract
{
    /**
     * @param string $vonageKey    The Vonage api key
     * @param string $vonageSecret The Vonage api secret
     */
    public function __construct(
        public readonly string $vonageKey = 'vonage-key',
        public readonly string $vonageSecret = 'vonage-secret',
    ) {
    }
}
