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
use Valkyrja\Type\Model\Abstract\Model as AbstractModel;
use Valkyrja\Type\Model\Contract\ExposableModelContract as Contract;
use Valkyrja\Type\Model\Trait\Exposable;

/**
 * Model class to use to test Exposable model.
 *
 * @property string $protected
 */
class ExposableModelClass extends AbstractModel implements Contract
{
    use Exposable;
    use PrivatePropertyTrait;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getExposable(): array
    {
        return [
            ModelClass::PRIVATE,
        ];
    }
}
