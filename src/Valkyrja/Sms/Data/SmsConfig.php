<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Sms\Data;

use Valkyrja\Sms\Data\Contract\SmsConfigContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\VonageMessenger;

class SmsConfig implements SmsConfigContract
{
    /**
     * @param class-string<MessengerContract> $defaultMessenger The messenger to use by default
     */
    public function __construct(
        public readonly string $defaultMessenger = VonageMessenger::class,
    ) {
    }
}
