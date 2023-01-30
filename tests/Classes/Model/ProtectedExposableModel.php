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

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Model\ExposableModel as Contract;
use Valkyrja\Model\Models\Model as AbstractModel;
use Valkyrja\Model\Models\ProtectedExposable;

/**
 * Model class to use to test ProtectedExposable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ProtectedExposableModel extends AbstractModel implements Contract
{
    use ProtectedExposable;
    use PrivateProperty;

    public string $public;

    protected string $protected;

    public static function getExposable(): array
    {
        return [
            Model::PROTECTED,
            Model::PRIVATE,
        ];
    }
}
