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

namespace Valkyrja\Annotation\Config;

use Valkyrja\Annotation\Config as Model;
use Valkyrja\Annotation\Constant\ConfigValue;

/**
 * Class Annotation.
 */
class Annotation extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(?array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);
    }
}
