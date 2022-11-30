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

namespace Valkyrja\Support\Type;

use Valkyrja\Support\Type\Enums\VlidVersion;
use Valkyrja\Support\Type\Exceptions\InvalidVlidV1Exception;

/**
 * Class VlidV1.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and can sacrifice on randomness a
 * little while remaining 128 bit.
 *
 * @author Melech Mizrachi
 */
class VlidV1 extends Vlid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{12}[1][' . self::VALID_CHARACTERS . ']{12}';

    public const VERSION = VlidVersion::V1;

    protected const FORMAT = '%013s%01s%04s%04s%04s';

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidV1Exception("Invalid VLID V1 $uid provided.");
    }
}
