<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum Stream.
 *
 * @author Melech Mizrachi
 */
final class Stream extends Enum
{
    public const INPUT = 'php://input';
    public const TEMP  = 'php://temp';
}