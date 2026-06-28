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

use Valkyrja\Type\Model\Abstract\Model;

/**
 * Model class to use to test abstract model.
 *
 * @property string $protected
 */
final class SimpleModelClass extends Model
{
    protected string $protected;
}
