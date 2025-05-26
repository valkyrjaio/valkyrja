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

namespace Valkyrja\Jwt\Config;

use Valkyrja\Jwt\Adapter\Firebase\RsAdapter;
use Valkyrja\Jwt\Constant\ConfigName;
use Valkyrja\Jwt\Enum\Algorithm;

/**
 * Class RsConfiguration.
 *
 * @author Melech Mizrachi
 */
class RsConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ALGORITHM     => 'JWT_RS_ALGORITHM',
        ConfigName::ADAPTER_CLASS => 'JWT_RS_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'JWT_RS_DRIVER_CLASS',
        ConfigName::PRIVATE_KEY   => 'JWT_RS_PRIVATE_KEY',
        ConfigName::PUBLIC_KEY    => 'JWT_RS_PUBLIC_KEY',
        ConfigName::KEY_PATH      => 'JWT_RS_KEY_PATH',
        ConfigName::PASSPHRASE    => 'JWT_RS_PASSPHRASE',
    ];

    public function __construct(
        public string $privateKey = '',
        public string $publicKey = '',
        public string $keyPath = '',
        public string $passphrase = '',
    ) {
        parent::__construct(
            algorithm: Algorithm::RS256,
            adapterClass: RsAdapter::class,
        );
    }
}
