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
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

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
        CKP::HOST,
        CKP::PORT,
        CKP::FROM_ADDRESS,
        CKP::FROM_NAME,
        CKP::ENCRYPTION,
        CKP::USERNAME,
        CKP::PASSWORD,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::HOST         => EnvKey::MAIL_HOST,
        CKP::PORT         => EnvKey::MAIL_PORT,
        CKP::FROM_ADDRESS => EnvKey::MAIL_FROM_ADDRESS,
        CKP::FROM_NAME    => EnvKey::MAIL_FROM_NAME,
        CKP::ENCRYPTION   => EnvKey::MAIL_ENCRYPTION,
        CKP::USERNAME     => EnvKey::MAIL_USERNAME,
        CKP::PASSWORD     => EnvKey::MAIL_PASSWORD,
    ];

    /**
     * The host.
     *
     * @var string
     */
    public string $host;

    /**
     * The port.
     *
     * @var int
     */
    public int $port;

    /**
     * The from address.
     *
     * @var string
     */
    public string $fromAddress;

    /**
     * The from name.
     *
     * @var string
     */
    public string $fromName;

    /**
     * The encryption.
     *
     * @var string
     */
    public string $encryption;

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
}
