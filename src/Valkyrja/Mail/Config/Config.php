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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Manager\MessageConfig as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::DEFAULT         => EnvKey::MAIL_DEFAULT,
        CKP::DEFAULT_MESSAGE => EnvKey::MAIL_DEFAULT_MESSAGE,
        CKP::ADAPTER         => EnvKey::MAIL_ADAPTER,
        CKP::DRIVER          => EnvKey::MAIL_DRIVER,
        CKP::MESSAGE         => EnvKey::MAIL_MESSAGE,
        CKP::MAILERS         => EnvKey::MAIL_MAILERS,
        CKP::MESSAGES        => EnvKey::MAIL_MESSAGES,
    ];

    /**
     * @inheritDoc
     */
    public string $default;

    /**
     * @inheritDoc
     */
    public string $adapter;

    /**
     * @inheritDoc
     */
    public string $driver;

    /**
     * @inheritDoc
     */
    public string $message;

    /**
     * The mailers.
     *
     * @var array[]
     */
    public array $mailers;

    /**
     * @inheritDoc
     *
     * @var array[]
     */
    public array $messages;
}
