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

use Valkyrja\Type\Model\Contract\CastableModel as Contract;

/**
 * Class CastableModel.
 *
 * @author Melech Mizrachi
 */
abstract class CastableModel extends Model implements Contract
{
    use Castable;
}
