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

namespace Valkyrja\Crypt\Config;

use Valkyrja\Crypt\Adapter\NullAdapter;
use Valkyrja\Crypt\Constant\ConfigName;
use Valkyrja\Crypt\Constant\EnvName;

/**
 * Class NullConfiguration.
 *
 * @author Melech Mizrachi
 */
class NullConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::NULL_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::NULL_DRIVER_CLASS,
        ConfigName::KEY           => EnvName::NULL_KEY,
    ];

    public function __construct(
        string $key = '',
    ) {
        parent::__construct(
            key: $key,
            adapterClass: NullAdapter::class,
        );
    }
}
