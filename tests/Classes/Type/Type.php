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

namespace Valkyrja\Tests\Classes\Type;

use Valkyrja\Type\Types\Type as AbstractType;

/**
 * Type class to use to test abstract type.
 *
 * @author Melech Mizrachi
 */
class Type extends AbstractType
{
    public function asFlatValue(): string|int|float|bool|null
    {
        return $this->asValue();
    }
}
