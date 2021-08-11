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

namespace Valkyrja\ORM\Entities;

use Valkyrja\ORM\DatedEntity as EntityContract;

/**
 * Class DatedEntity.
 *
 * @author Melech Mizrachi
 */
class DatedEntity extends Entity implements EntityContract
{
    use DatedEntityFields;
}
