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

namespace Valkyrja\Dispatcher\Data\Contract;

use JsonSerializable;
use Stringable;

/**
 * Interface Dispatch.
 *
 * @author Melech Mizrachi
 */
interface Dispatch extends JsonSerializable, Stringable
{
    /**
     * Get the Dispatch as a string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Serialize properties for json_encode.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
