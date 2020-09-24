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
     * The date created field.
     *
     * @var string
     */
    protected static string $dateCreatedField = 'date_created';

    /**
     * The date modified field.
     *
     * @var string
     */
    protected static string $dateModifiedField = 'date_modified';

    /**
     * Get the date created field.
     *
     * @return string
     */
    public static function getDateCreatedField(): string
    {
        return static::$dateCreatedField;
    }

    /**
     * Get the date modified field.
     *
     * @return string
     */
    public static function getDateModifiedField(): string
    {
        return static::$dateModifiedField;
    }

    /**
     * Get the date created field value.
     *
     * @return string
     */
    public function getDateCreatedFieldValue(): string
    {
        return $this->{static::$dateCreatedField};
    }

    /**
     * Set the date created field value.
     *
     * @param string $createdAt
     *
     * @return void
     */
    public function setDateCreatedFieldValue(string $createdAt): void
    {
        $this->{static::$dateCreatedField} = $createdAt;
    }

    /**
     * Get the date modified field value.
     *
     * @return string
     */
    public function getDateModifiedFieldValue(): string
    {
        return $this->{static::$dateModifiedField};
    }

    /**
     * Set the date modified field value.
     *
     * @param string $updatedAt
     *
     * @return void
     */
    public function setDateModifiedFieldValue(string $updatedAt): void
    {
        $this->{static::$dateModifiedField} = $updatedAt;
    }
}
