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
        CKP::USERNAME,
        CKP::PASSWORD,
        CKP::ADAPTER,
        CKP::ADAPTERS,
        CKP::MESSAGE,
        CKP::MESSAGES,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::USERNAME => EnvKey::SMS_USERNAME,
        CKP::PASSWORD => EnvKey::SMS_PASSWORD,
        CKP::ADAPTER  => EnvKey::SMS_ADAPTER,
        CKP::ADAPTERS => EnvKey::SMS_ADAPTERS,
        CKP::MESSAGE  => EnvKey::SMS_MESSAGE,
        CKP::MESSAGES => EnvKey::SMS_MESSAGES,
    ];

    /**
     * The username.
     *
     * @var string
     */
    public string $username;

    /**
     * The password.
     *
     * @var string
     */
    public string $password;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The default message.
     *
     * @var string
     */
    public string $message;

    /**
     * The message adapters.
     *
     * @var string[]
     */
    public array $messages;
}
