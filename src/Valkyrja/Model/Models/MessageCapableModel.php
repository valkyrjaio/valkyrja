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

namespace Valkyrja\Model\Models;

use Valkyrja\Model\MessageCapableModel as Contract;

/**
 * Class MessageCapableModel.
 *
 * @author Melech Mizrachi
 */
abstract class MessageCapableModel extends Model implements Contract
{
    use MessageCapable;
}
