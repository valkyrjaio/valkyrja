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

use Valkyrja\Model\ExposableModel as Contract;
use Valkyrja\Model\Models\Exposable;
use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test Exposable trait directly.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class SimpleExposableModel extends AbstractModel implements Contract
{
    use Exposable;
}
