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

namespace Valkyrja\SMS\Config;

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
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::DEFAULT,
        CKP::ADAPTERS,
        CKP::DRIVERS,
        CKP::MESSENGERS,
        CKP::DEFAULT_MESSAGE,
        CKP::MESSAGE_ADAPTERS,
        CKP::MESSAGES,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::DEFAULT          => EnvKey::SMS_DEFAULT,
        CKP::ADAPTERS         => EnvKey::SMS_ADAPTERS,
        CKP::DRIVERS          => EnvKey::SMS_DRIVERS,
        CKP::MESSENGERS       => EnvKey::SMS_MESSENGERS,
        CKP::DEFAULT_MESSAGE  => EnvKey::SMS_DEFAULT_MESSAGE,
        CKP::MESSAGE_ADAPTERS => EnvKey::SMS_MESSAGE_ADAPTERS,
        CKP::MESSAGES         => EnvKey::SMS_MESSAGES,
    ];

    /**
     * The default messenger.
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
     * The messengers.
     *
     * @var array[]
     */
    public array $messengers;

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
