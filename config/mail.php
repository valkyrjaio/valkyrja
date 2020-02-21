<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Mail Configuration
 *-------------------------------------------------------------------------
 *
 * //
 *
 */

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

return [
    /*
     *-------------------------------------------------------------------------
     * SMTP Host Address
     *-------------------------------------------------------------------------
     *
     * Here you may provide the host address of the SMTP server used by your
     * applications.
     *
     */
    CKP::HOST       => env(EnvKey::MAIL_HOST, 'smtp1.example.com;smtp2.example.com'),

    /*
     *--------------------------------------------------------------------------
     * SMTP Host Port
     *--------------------------------------------------------------------------
     *
     * This is the SMTP port used by your application to deliver e-mails to
     * users of the application.
     *
     */
    CKP::PORT       => env(EnvKey::MAIL_PORT, 587),

    /*
     *--------------------------------------------------------------------------
     * Global "From" Address
     *--------------------------------------------------------------------------
     *
     * You may wish for all e-mails sent by your application to be sent from
     * the same address. Here, you may specify a name and address that is
     * used globally for all e-mails that are sent by your application.
     *
     */
    CKP::FROM       => [
        CKP::ADDRESS => env(EnvKey::MAIL_FROM_ADDRESS, 'hello@example.com'),
        CKP::NAME    => env(EnvKey::MAIL_FROM_NAME, 'Example'),
    ],

    /*
     *--------------------------------------------------------------------------
     * E-Mail Encryption Protocol
     *--------------------------------------------------------------------------
     *
     * Here you may specify the encryption protocol that should be used when
     * the application send e-mail messages. A sensible default using the
     * transport layer security protocol should provide great security.
     *
     */
    CKP::ENCRYPTION => env(EnvKey::MAIL_ENCRYPTION, 'tls'),

    /*
     *--------------------------------------------------------------------------
     * SMTP Server Username
     *--------------------------------------------------------------------------
     *
     * If your SMTP server requires a username for authentication, you should
     * set it here. This will get used to authenticate with your server on
     * connection. You may also set the "password" value below this one.
     *
     */
    CKP::USERNAME   => env(EnvKey::MAIL_USERNAME),
    CKP::PASSWORD   => env(EnvKey::MAIL_PASSWORD),

];
