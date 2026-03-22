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

namespace Valkyrja\Tests\Classes\Cli\Routing\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Cli\Routing\Data\Contract\ConfigContract;

final class ConfigClass extends Config implements ConfigContract
{
    /**
     * @param non-empty-string $dataClassName
     */
    public function __construct(
        public string $dataClassName = '',
    ) {
        parent::__construct();
    }
}
