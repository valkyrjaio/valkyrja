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

namespace Valkyrja\Client\Config;

use Valkyrja\Client\Adapters\GuzzleAdapter;
use Valkyrja\Client\Drivers\Driver;
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
        CKP::DEFAULT => EnvKey::CLIENT_DEFAULT,
        CKP::ADAPTER => EnvKey::CLIENT_ADAPTER,
        CKP::DRIVER  => EnvKey::CLIENT_DRIVER,
        CKP::CLIENTS => EnvKey::CLIENT_CLIENTS,
    ];

    /**
     * The default client connection.
     *
     * @var string
     */
    public string $default = CKP::GUZZLE;

    /**
     * The adapter.
     *
     * @var string
     */
    public string $adapter = GuzzleAdapter::class;

    /**
     * The driver.
     *
     * @var string
     */
    public string $driver = Driver::class;

    /**
     * The client connections.
     *
     * @var array
     */
    public array $clients = [
        CKP::GUZZLE => [
            CKP::ADAPTER => null,
            CKP::DRIVER  => null,
            CKP::OPTIONS => [],
        ],
    ];
}
