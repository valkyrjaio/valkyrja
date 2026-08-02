<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Enum;

use JsonSerializable;
use Override;

enum Type implements JsonSerializable
{
    case array;
    case object;
    case string;
    case int;
    case float;
    case bool;
    case true;
    case false;
    case null;

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
