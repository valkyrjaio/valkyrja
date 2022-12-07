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

namespace Valkyrja\Routing\Enums;

use JsonSerializable;

/**
 * Enum CastType.
 *
 * @author Melech Mizrachi
 */
enum CastType: string implements JsonSerializable
{
    case string = 'string';
    case int    = 'int';
    case float  = 'float';
    case bool   = 'bool';
    case enum   = 'enum';
    case entity = 'entity';

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
