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

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Session\Constant\ConfigName;

/**
 * Class CookieParamsConfiguration.
 *
 * @author Melech Mizrachi
 */
class CookieParamsConfiguration extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::PATH      => 'SESSION_COOKIE_PARAM_PATH',
        ConfigName::DOMAIN    => 'SESSION_COOKIE_PARAM_DOMAIN',
        ConfigName::LIFETIME  => 'SESSION_COOKIE_PARAM_LIFETIME',
        ConfigName::SECURE    => 'SESSION_COOKIE_PARAM_SECURE',
        ConfigName::HTTP_ONLY => 'SESSION_COOKIE_PARAM_HTTP_ONLY',
        ConfigName::SAME_SITE => 'SESSION_COOKIE_PARAM_SAME_SITE',
    ];

    public function __construct(
        public string $path = '/',
        public string|null $domain = null,
        public int $lifetime = 0,
        public bool $secure = false,
        public bool $httpOnly = false,
        public SameSite $sameSite = SameSite::NONE,
    ) {
    }
}
