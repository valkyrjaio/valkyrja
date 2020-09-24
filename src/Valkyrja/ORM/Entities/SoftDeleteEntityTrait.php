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
 * Trait SoftDeleteEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait SoftDeleteEntityTrait
{
    /**
     * The deleted field.
     *
     * @var string
     */
    protected static string $deletedField = 'deleted';

    /**
     * The date deleted field.
     *
     * @var string
     */
    protected static string $dateDeletedField = 'date_deleted';

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
     * Get the date deleted field.
     *
     * @return string
     */
    public static function getDateDeletedField(): string
    {
        return static::$dateDeletedField;
    }

    /**
     * Get the deleted field value.
     *
     * @return bool
     */
    public function getDeletedFieldValue(): bool
    {
        return (bool) $this->{static::$deletedField};
    }

    /**
     * Set the deleted field value.
     *
     * @param bool $deleted
     *
     * @return void
     */
    public function setDeletedFieldValue(bool $deleted): void
    {
        $this->{static::$deletedField} = $deleted;
    }

    /**
     * Get the date deleted field value.
     *
     * @return string|null
     */
    public function getDateDeletedFieldValue(): ?string
    {
        return (string) $this->{static::$dateDeletedField};
    }

    /**
     * Set the date deleted field value.
     *
     * @param string|null $deletedAt
     *
     * @return void
     */
    public function setDateDeletedFieldValue(string $deletedAt = null): void
    {
        $this->{static::$dateDeletedField} = $deletedAt;
    }
}
