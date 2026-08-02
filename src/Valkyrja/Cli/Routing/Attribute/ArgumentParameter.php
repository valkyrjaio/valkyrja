<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Attribute;

use Attribute;
use Valkyrja\Cli\Routing\Data\ArgumentParameter as Model;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ArgumentParameter extends Model
{
}
