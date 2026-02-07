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

namespace Valkyrja\Tests\Classes\Type\Model;

use Valkyrja\Tests\Classes\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\Exposable;

/**
 * Model class to use to test Exposable model.
 *
 * @property string $protected
 */
final class ExposableModelClass extends Model implements ExposableModelContract
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
