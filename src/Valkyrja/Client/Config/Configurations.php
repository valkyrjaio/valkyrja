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

/**
 * Class Configurations.
 *
 * @author Melech Mizrachi
 */
class Configurations
{
    public function __construct(
        public GuzzleConfiguration|null $guzzle = null,
        public LogConfiguration|null $log = null,
        public NullConfiguration|null $null = null,
    ) {
    }
}
