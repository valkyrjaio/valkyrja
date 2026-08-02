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

use Valkyrja\Mail\Data\Contract\MailConfigContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\MailgunMailer;

class MailConfig implements MailConfigContract
{
    /**
     * @param class-string<MailerContract> $defaultMailer The mailer to use by default
     */
    public function __construct(
        public readonly string $defaultMailer = MailgunMailer::class,
    ) {
    }
}
