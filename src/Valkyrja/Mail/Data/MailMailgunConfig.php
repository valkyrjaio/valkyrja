<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data;

use Valkyrja\Mail\Data\Contract\MailMailgunConfigContract;

class MailMailgunConfig implements MailMailgunConfigContract
{
    /**
     * @param non-empty-string $mailgunDomain The Mailgun domain to send from
     * @param string           $mailgunApiKey The Mailgun api key
     */
    public function __construct(
        public readonly string $mailgunDomain = 'domain',
        public readonly string $mailgunApiKey = 'api-key',
    ) {
    }
}
