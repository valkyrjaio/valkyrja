<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Routing\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\CliRoutingConfigContract;

final class CliRoutingConfigFixture extends Config implements CliRoutingConfigContract
{
    /**
     * @param non-empty-string $dataClassName
     */
    public function __construct(
        public string $dataClassName = CliRoutingData::class,
    ) {
        parent::__construct();
    }
}
