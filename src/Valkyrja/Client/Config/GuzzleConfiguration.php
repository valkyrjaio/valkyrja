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

use Valkyrja\Client\Adapter\GuzzleAdapter;
use Valkyrja\Client\Constant\ConfigName;

/**
 * Class GuzzleConfiguration.
 *
 * @author Melech Mizrachi
 */
class GuzzleConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'CLIENT_GUZZLE_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'CLIENT_GUZZLE_DRIVER_CLASS',
        ConfigName::OPTIONS       => 'CLIENT_GUZZLE_OPTIONS',
    ];

    /**
     * @param array<string, mixed> $options [optional] Options passed directly to the Guzzle client
     */
    public function __construct(
        public array $options = [],
    ) {
        parent::__construct(
            adapterClass: GuzzleAdapter::class,
        );
    }
}
