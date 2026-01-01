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

use Valkyrja\Type\Model\Abstract\Model as AbstractModel;
use Valkyrja\Type\Model\Contract\ExposableModelContract as Contract;
use Valkyrja\Type\Model\Trait\Exposable;

/**
 * Model class to use to test Exposable trait directly.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class SimpleExposableModelClass extends AbstractModel implements Contract
{
    use Exposable;
}
