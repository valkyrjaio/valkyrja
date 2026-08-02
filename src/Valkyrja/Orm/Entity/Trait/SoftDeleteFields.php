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

namespace Valkyrja\Orm\Entity\Trait;

trait SoftDeleteFields
{
    /**
     * The date the entity was soft deleted.
     *
     * @var string|null
     */
    public string|null $deleted_at = null;
}
