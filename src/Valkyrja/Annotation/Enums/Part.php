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

namespace Valkyrja\Annotation\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Part.
 *
 * @author Melech Mizrachi
 */
final class Part extends Enum
{
    public const TYPE          = Property::TYPE;
    public const PROPERTIES    = Property::PROPERTIES;
    public const VARIABLE_TYPE = 'variableType';
    public const VARIABLE      = 'variable';
    public const DESCRIPTION   = 'description';
}
