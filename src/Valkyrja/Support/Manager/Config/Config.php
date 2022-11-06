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

namespace Valkyrja\Support\Manager\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Support\Manager\Adapter;
use Valkyrja\Support\Manager\Driver;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * The default configuration.
     *
     * @var string
     */
    public string $default = CKP::DEFAULT;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter = Adapter::class;

    /**
     * The default driver.
     *
     * @var string
     */
    public string $driver = Driver::class;

    /**
     * The configurations.
     *
     * @var array[]
     */
    public array $configurations = [
        CKP::DEFAULT => [
            CKP::ADAPTER => null,
            CKP::DRIVER  => null,
        ],
    ];
}
