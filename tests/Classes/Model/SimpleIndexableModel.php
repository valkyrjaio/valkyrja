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

use Valkyrja\Type\Model\Contract\IndexedModel as Contract;
use Valkyrja\Type\Model\Indexable;
use Valkyrja\Type\Model\Model as AbstractModel;

/**
 * Model class to use to test Indexable model directly.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class SimpleIndexableModel extends AbstractModel implements Contract
{
    use Indexable;
}
