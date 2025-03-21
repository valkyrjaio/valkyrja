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

namespace Valkyrja\Orm\Entity;

use Valkyrja\Orm\Entity\Contract\SoftDeleteEntity as Contract;

/**
 * Class SoftDeleteEntity.
 *
 * @author Melech Mizrachi
 */
abstract class SoftDeleteEntity extends Entity implements Contract
{
    use SoftDeleteFields;
}
