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

use Valkyrja\Type\Model\Contract\ExposableModel as Contract;
use Valkyrja\Type\Model\Exposable;
use Valkyrja\Type\Model\Model as AbstractModel;

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

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getExposable(): array
    {
        return [
            Model::PRIVATE,
        ];
    }
}
