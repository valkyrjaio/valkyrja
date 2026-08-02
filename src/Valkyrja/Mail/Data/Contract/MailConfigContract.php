<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data\Contract;

use Valkyrja\Mail\Mailer\Contract\MailerContract;

interface MailConfigContract
{
    /** @var class-string<MailerContract> */
    public string $defaultMailer {
        get;
    }
}
