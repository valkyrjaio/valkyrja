<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Entities;

/**
 * Trait DatedEntityFields.
 *
 * @author Melech Mizrachi
 */
trait DatedEntityFields
{
    use DatedEntityTrait;

    /**
     * Created at column.
     *
     * @var bool
     */
    public bool $created_at;

    /**
     * Updated at column.
     *
     * @var string
     */
    public string $updated_at;
}
