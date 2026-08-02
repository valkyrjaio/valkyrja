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

/**
 * Model class to use to test abstract model.
 *
 * @property string $protected
 */
final class SimpleModelFixture extends Model
{
    protected string $protected;
}
