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

namespace Valkyrja\Sms\Data\Contract;

use Valkyrja\Sms\Data\SmsVonageConfig;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;

interface SmsConfigContract
{
    /** @var class-string<MessengerContract> */
    public string $defaultMessenger {
        get;
    }

    public SmsVonageConfig $vonage {
        get;
    }
}
