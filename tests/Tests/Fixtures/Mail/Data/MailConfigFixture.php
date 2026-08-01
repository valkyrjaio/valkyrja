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

namespace Valkyrja\Tests\Fixtures\Mail\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Mail\Data\Contract\MailConfigContract;
use Valkyrja\Mail\Data\Contract\MailMailgunConfigContract;
use Valkyrja\Mail\Data\Contract\MailPhpMailerConfigContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract;
use Valkyrja\Mail\Mailer\NullMailer;

/**
 * An application config that implements every mail contract at once.
 *
 * The adapter contracts prefix each property with the adapter name, so one class
 * can carry the settings for several adapters without a name collision.
 */
final class MailConfigFixture extends Config implements MailConfigContract, MailMailgunConfigContract, MailPhpMailerConfigContract
{
    /**
     * @param class-string<MailerContract> $defaultMailer
     * @param non-empty-string             $mailgunDomain
     */
    public function __construct(
        public string $defaultMailer = NullMailer::class,
        public string $mailgunDomain = 'test-domain',
        public string $mailgunApiKey = 'test-api-key',
        public string $phpMailerHost = 'test-host',
        public int $phpMailerPort = 587,
        public string $phpMailerUsername = 'test-username',
        public string $phpMailerPassword = 'test-password',
        public string $phpMailerEncryption = 'tls',
    ) {
        parent::__construct();
    }
}
