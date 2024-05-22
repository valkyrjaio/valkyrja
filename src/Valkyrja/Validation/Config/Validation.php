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

namespace Valkyrja\Validation\Config;

use Valkyrja\Validation\Config as Model;
use Valkyrja\Validation\Constant\ConfigValue;

/**
 * Class Validation.
 */
class Validation extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->rulesMap = array_merge($this->rulesMap, []);
    }
}
