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

namespace Valkyrja\Session\Config;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Session\Adapters\PHPAdapter;
use Valkyrja\Session\Drivers\Driver;
use Valkyrja\Support\Manager\Config\Config as Model;

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
        CKP::DEFAULT  => EnvKey::SESSION_DEFAULT,
        CKP::ADAPTER  => EnvKey::SESSION_ADAPTER,
        CKP::DRIVER   => EnvKey::SESSION_DRIVER,
        CKP::SESSIONS => EnvKey::SESSION_SESSIONS,
    ];

    /**
     * @inheritDoc
     */
    public string $adapter = PHPAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * The sessions.
     *
     * @var array
     */
    public array $sessions = [
        CKP::DEFAULT => [
            CKP::ADAPTER       => null,
            CKP::DRIVER        => null,
            CKP::ID            => null,
            CKP::NAME          => null,
            /**
             * @example
             *  [
             *      'lifetime' => 600,
             *      'path'     => '/',
             *      'domain'   => 'example.com',
             *      'secure'   => true,
             *      'httponly' => true,
             *      'samesite' => 'lax',
             *  ]
             */
            CKP::COOKIE_PARAMS => null,
        ],
    ];
}
