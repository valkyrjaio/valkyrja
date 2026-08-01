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

namespace Valkyrja\Tests\Fixtures\Sms\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Sms\Data\Contract\SmsConfigContract;
use Valkyrja\Sms\Data\SmsVonageConfig;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\NullMessenger;

final class SmsConfigFixture extends Config implements SmsConfigContract
{
    /**
     * @param class-string<MessengerContract> $defaultMessenger
     */
    public function __construct(
        public string $defaultMessenger = NullMessenger::class,
        public SmsVonageConfig $vonage = new SmsVonageConfig(),
    ) {
        parent::__construct();
    }
}
