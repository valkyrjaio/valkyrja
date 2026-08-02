<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Abstract;

use Valkyrja\Type\Model\Contract\IndexedModelContract;
use Valkyrja\Type\Model\Trait\Indexable;

abstract class IndexedModel extends Model implements IndexedModelContract
{
    use Indexable;
}
