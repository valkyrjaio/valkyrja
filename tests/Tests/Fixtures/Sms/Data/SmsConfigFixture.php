<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
