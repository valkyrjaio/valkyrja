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

namespace Valkyrja\Mail\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT          => EnvKey::MAIL_DEFAULT,
        CKP::ADAPTERS         => EnvKey::MAIL_ADAPTERS,
        CKP::DRIVERS          => EnvKey::MAIL_DRIVERS,
        CKP::MAILERS          => EnvKey::MAIL_MAILERS,
        CKP::DEFAULT_MESSAGE  => EnvKey::MAIL_DEFAULT_MESSAGE,
        CKP::MESSAGE_ADAPTERS => EnvKey::MAIL_MESSAGE_ADAPTERS,
        CKP::MESSAGES         => EnvKey::MAIL_MESSAGES,
    ];

    /**
     * The default mailer.
     *
     * @var string
     */
    public string $default;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The drivers.
     *
     * @var string[]
     */
    public array $drivers;

    /**
     * The mailers.
     *
     * @var array[]
     */
    public array $mailers;

    /**
     * The default message.
     *
     * @var string
     */
    public string $defaultMessage;

    /**
     * The message adapters.
     *
     * @var string[]
     */
    public array $messageAdapters;

    /**
     * The messages.
     *
     * @var array[]
     */
    public array $messages;
}
