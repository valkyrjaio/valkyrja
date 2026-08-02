<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Model;

use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\IndexedModelContract;
use Valkyrja\Type\Model\Trait\Indexable;

/**
 * Model class to use to test Indexable model directly.
 *
 * @property string $protected
 */
final class SimpleIndexableModelFixture extends Model implements IndexedModelContract
{
    use Indexable;
}
