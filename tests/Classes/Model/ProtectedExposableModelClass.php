<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Tests\Classes\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Contract\ExposableModel as Contract;
use Valkyrja\Type\Model\Model as AbstractModel;
use Valkyrja\Type\Model\ProtectedExposable;

/**
 * Model class to use to test ProtectedExposable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ProtectedExposableModelClass extends AbstractModel implements Contract
{
    use PrivatePropertyTrait;
    use ProtectedExposable;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    public static function getExposable(): array
    {
        return [
            ModelClass::PROTECTED,
            ModelClass::PRIVATE,
        ];
    }
}
