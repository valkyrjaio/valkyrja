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

namespace Valkyrja\Dispatch\Data\Contract;

use JsonSerializable;
use Override;
use Stringable;

/**
 * Interface DispatchContract.
 *
 * @author Melech Mizrachi
 */
interface DispatchContract extends JsonSerializable, Stringable
{
    /**
     * Get the Dispatch as a string.
     *
     * @return non-empty-string
     */
    public function __toString(): string;

    /**
     * Serialize properties for json_encode.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function jsonSerialize(): array;
}
