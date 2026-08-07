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

use function is_string;

/**
 * Model class to use to test UnpackForNewInstance model.
 *
 * @property string $protected
 */
final class UnpackForNewInstanceModelFixture extends Model
{
    use PrivatePropertyTrait;
    use UnpackForNewInstance;

    public string $public;

    protected string $protected;

    public function __construct(mixed $public = '', mixed $protected = '')
    {
        $this->public    = is_string($public) ? $public : '';
        $this->protected = is_string($protected) ? $protected : '';
    }
}
