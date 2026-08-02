<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Vendor\Vonage;

use Vonage\Client;
use Vonage\SMS\Client as SmsClient;

class ClientFixture extends Client
{
    public function sms(): SmsClient
    {
        return parent::sms();
    }
}
