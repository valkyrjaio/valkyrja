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

trait DatedFields
{
    /**
     * The date the entity was created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The date the entity was last modified.
     *
     * @var string
     */
    public string $updated_at;
}
