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
     * Get the date created field.
     *
     * @return string
     */
    public static function getDateCreatedField(): string
    {
        return 'date_created';
    }

    /**
     * Get the date modified field.
     *
     * @return string
     */
    public static function getDateModifiedField(): string
    {
        return 'date_modified';
    }

    /**
     * Get the date created field value.
     *
     * @return string
     */
    public function getDateCreatedFieldValue(): string
    {
        return $this->{static::getDateCreatedField()};
    }

    /**
     * Set the date created field value.
     *
     * @param string $dateCreated
     *
     * @return void
     */
    public function setDateCreatedFieldValue(string $dateCreated): void
    {
        $this->{static::getDateCreatedField()} = $dateCreated;
    }

    /**
     * Get the date modified field value.
     *
     * @return string
     */
    public function getDateModifiedFieldValue(): string
    {
        return $this->{static::getDateModifiedField()};
    }

    /**
     * Set the date modified field value.
     *
     * @param string $dateModified
     *
     * @return void
     */
    public function setDateModifiedFieldValue(string $dateModified): void
    {
        $this->{static::getDateModifiedField()} = $dateModified;
    }
}
