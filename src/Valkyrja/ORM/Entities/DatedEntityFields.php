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
     * The created at date.
     *
     * @var bool
     */
    public bool $created_at;

    /**
     * The updated at date.
     *
     * @var string
     */
    public string $updated_at;
}
