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

namespace Valkyrja\Log\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Log\Adapters\PsrAdapter;
use Valkyrja\Log\Drivers\Driver;

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
        CKP::DEFAULT => EnvKey::LOG_DEFAULT,
        CKP::ADAPTER => EnvKey::LOG_ADAPTER,
        CKP::DRIVER  => EnvKey::LOG_DRIVER,
        CKP::LOGGERS => EnvKey::LOG_LOGGERS,
    ];

    /**
     * The default logger.
     *
     * @var string
     */
    public string $default = CKP::PSR;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter = PsrAdapter::class;

    /**
     * The default driver.
     *
     * @var string
     */
    public string $driver = Driver::class;

    /**
     * The loggers.
     *
     * @var array[]
     */
    public array $loggers = [
        CKP::PSR => [
            CKP::ADAPTER   => null,
            CKP::NAME      => 'application-log',
            CKP::FILE_PATH => '',
        ],
    ];
}
