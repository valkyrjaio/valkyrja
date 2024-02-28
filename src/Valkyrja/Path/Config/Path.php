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

namespace Valkyrja\Path\Config;

use Valkyrja\Path\Config\Config as Model;
use Valkyrja\Path\Constants\ConfigValue;

/**
 * Class Path.
 */
class Path extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->patterns = array_merge(ConfigValue::PATTERNS, []);
    }
}
