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

/**
 * Trait DatedEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait DatedEntityTrait
{
    /**
     * The created at field.
     *
     * @var string
     */
    protected static string $createdAtField = 'created_at';

    /**
     * The updated at field.
     *
     * @var string
     */
    protected static string $updatedAtField = 'deleted_at';

    /**
     * Get the created at field.
     *
     * @return string
     */
    public static function getCreatedAtField(): string
    {
        return static::$createdAtField;
    }

    /**
     * Get the updated at field.
     *
     * @return string
     */
    public static function getUpdatedAtField(): string
    {
        return static::$updatedAtField;
    }

    /**
     * Get the created at field value.
     *
     * @return string
     */
    public function getCreatedAtFieldValue(): string
    {
        return $this->{static::$createdAtField};
    }

    /**
     * Set the created at field value.
     *
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAtFieldValue(string $createdAt): void
    {
        $this->{static::$createdAtField} = $createdAt;
    }

    /**
     * Get the updated at field value.
     *
     * @return string
     */
    public function getUpdatedAtFieldValue(): string
    {
        return $this->{static::$updatedAtField};
    }

    /**
     * Set the updated at field value.
     *
     * @param string $updatedAt
     *
     * @return void
     */
    public function setUpdatedAtFieldValue(string $updatedAt): void
    {
        $this->{static::$updatedAtField} = $updatedAt;
    }
}
