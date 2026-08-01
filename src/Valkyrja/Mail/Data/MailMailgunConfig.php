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
