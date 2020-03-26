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
 * Trait SoftDeleteEntityFields.
 *
 * @author Melech Mizrachi
 */
trait SoftDeleteEntityFields
{
    use SoftDeleteEntityTrait;

    /**
     * The deleted flag.
     *
     * @var bool
     */
    public bool $deleted = false;

    /**
     * The deleted at date.
     *
     * @var string|null
     */
    public ?string $deleted_at = null;
}
