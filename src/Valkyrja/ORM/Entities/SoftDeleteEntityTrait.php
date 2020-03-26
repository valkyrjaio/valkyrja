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
 * Trait SoftDeleteEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait SoftDeleteEntityTrait
{
    use EntityTrait;

    /**
     * The deleted field.
     *
     * @var string
     */
    protected static string $deletedField = 'deleted';

    /**
     * The deleted at field.
     *
     * @var string
     */
    protected static string $deletedAtField = 'deleted_at';

    /**
     * Get the deleted field.
     *
     * @return string
     */
    public static function getDeletedField(): string
    {
        return static::$deletedField;
    }

    /**
     * Get the deleted at field.
     *
     * @return string
     */
    public static function getDeletedAtField(): string
    {
        return static::$deletedAtField;
    }
}
