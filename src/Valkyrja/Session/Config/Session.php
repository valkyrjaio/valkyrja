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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Session\Config\Config as Model;
use Valkyrja\Session\Constants\ConfigValue;

use function Valkyrja\env;

/**
 * Class Session.
 */
class Session extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->sessions = [
            CKP::DEFAULT => [
                CKP::ADAPTER       => null,
                CKP::DRIVER        => null,
                CKP::ID            => env(EnvKey::SESSION_ID),
                CKP::NAME          => env(EnvKey::SESSION_NAME, 'VALKYRJA_SESSION'),
                CKP::COOKIE_PARAMS => [
                    'lifetime' => env(EnvKey::SESSION_COOKIE_LIFETIME, 0),
                    'path'     => env(EnvKey::SESSION_COOKIE_PATH, '/'),
                    'domain'   => env(EnvKey::SESSION_COOKIE_DOMAIN, null),
                    'secure'   => env(EnvKey::SESSION_COOKIE_SECURE, false),
                    'httponly' => env(EnvKey::SESSION_COOKIE_HTTP_ONLY, false),
                    'samesite' => env(EnvKey::SESSION_COOKIE_SAME_SITE, ''),
                ],
            ],
        ];
    }
}
