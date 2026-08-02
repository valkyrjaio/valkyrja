<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Mailer;

use Override;
use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;

class NullMailer implements MailerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function send(MessageContract $message): void
    {
    }
}
