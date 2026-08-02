<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Attribute;

use Attribute;
use Valkyrja\Http\Routing\Data\Parameter as ParentParameter;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD)]
class Parameter extends ParentParameter
{
}
