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

use Valkyrja\Mail\Data\Contract\MailPhpMailerConfigContract;

class MailPhpMailerConfig implements MailPhpMailerConfigContract
{
    /**
     * @param string $phpMailerHost       The SMTP host to connect to
     * @param int    $phpMailerPort       The SMTP port to connect to
     * @param string $phpMailerUsername   The SMTP username
     * @param string $phpMailerPassword   The SMTP password
     * @param string $phpMailerEncryption The SMTP encryption to use
     */
    public function __construct(
        public readonly string $phpMailerHost = 'host',
        public readonly int $phpMailerPort = 25,
        public readonly string $phpMailerUsername = 'username',
        public readonly string $phpMailerPassword = 'password',
        public readonly string $phpMailerEncryption = 'ssl',
    ) {
    }
}
