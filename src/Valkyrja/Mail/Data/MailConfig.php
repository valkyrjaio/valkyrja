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
