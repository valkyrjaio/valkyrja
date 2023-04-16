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

namespace Valkyrja\Model\Enums;

use JsonSerializable;

/**
 * Enum CastType.
 *
 * @author Melech Mizrachi
 */
enum CastType implements JsonSerializable
{
    case string;
    case int;
    case float;
    case double;
    case bool;
    case true;
    case false;
    case null;
    case json;
    case array;
    case object;
    case model;
    case enum;
    case type;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
