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
use Valkyrja\Model\Models\Exposable;
use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test Exposable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ExposableModel extends AbstractModel implements Contract
{
    use Exposable;
    use PrivateProperty;

    public string $public;

    protected string $protected;

    public static function getExposable(): array
    {
        return [
            Model::PRIVATE,
        ];
    }
}
