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

use Valkyrja\Tests\Fixtures\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Trait\UnpackForNewInstance;

/**
 * Model class to use to test UnpackForNewInstance model.
 *
 * @property string $protected
 */
final class UnpackForNewInstanceModelFixture extends Model
{
    use PrivatePropertyTrait;
    use UnpackForNewInstance;

    public function __construct(
        public string $public = '',
        protected string $protected = '',
    ) {
    }
}
