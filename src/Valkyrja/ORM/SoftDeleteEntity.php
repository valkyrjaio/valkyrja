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

namespace Valkyrja\ORM;

/**
 * Interface SoftDeleteEntity.
 *
 * @author Melech Mizrachi
 */
interface SoftDeleteEntity extends Entity
{
    /**
     * Get the deleted field.
     *
     * @return string
     */
    public static function getDeletedField(): string;

    /**
     * Get the date deleted field.
     *
     * @return string
     */
    public static function getDateDeletedField(): string;

    /**
     * Get the deleted field value.
     *
     * @return bool
     */
    public function getDeletedFieldValue(): bool;

    /**
     * Set the deleted field value.
     *
     * @param bool $deleted
     *
     * @return void
     */
    public function setDeletedFieldValue(bool $deleted): void;

    /**
     * Get the date deleted field value.
     *
     * @return string|null
     */
    public function getDateDeletedFieldValue(): ?string;

    /**
     * Set the date deleted field value.
     *
     * @param string|null $dateDeleted
     *
     * @return void
     */
    public function setDateDeletedFieldValue(string $dateDeleted = null): void;
}
