<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message\Param\Abstract;

use Valkyrja\Http\Message\Param\Abstract\ParamCollection;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;

/**
 * @extends ParamCollection<non-empty-string|int, scalar|ParamCollectionContract|null>
 */
final class ParamCollectionFixture extends ParamCollection
{
}
