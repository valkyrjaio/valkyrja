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

namespace Valkyrja\Type\Model;

use Valkyrja\Type\Model\Contract\IndexedModel as Contract;
use Valkyrja\Type\Model\Trait\Indexable;

/**
 * Class IndexedModel.
 *
 * @author Melech Mizrachi
 */
abstract class IndexedModel extends Model implements Contract
{
    use Indexable;
}
