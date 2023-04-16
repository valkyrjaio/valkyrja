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

namespace Valkyrja\Type\Enums;

use JsonSerializable;

/**
 * Enum Type.
 *
 * @author Melech Mizrachi
 */
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
    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
