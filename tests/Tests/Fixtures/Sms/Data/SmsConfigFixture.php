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
use Valkyrja\Sms\Data\Contract\SmsVonageConfigContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Valkyrja\Sms\Messenger\NullMessenger;

/**
 * An application config that implements every sms contract at once.
 *
 * The adapter contracts prefix each property with the adapter name, so one class
 * can carry the settings for several adapters without a name collision.
 */
final class SmsConfigFixture extends Config implements SmsConfigContract, SmsVonageConfigContract
{
    /**
     * @param class-string<MessengerContract> $defaultMessenger
     */
    public function __construct(
        public string $defaultMessenger = NullMessenger::class,
        public string $vonageKey = 'test-key',
        public string $vonageSecret = 'test-secret',
    ) {
        parent::__construct();
    }
}
