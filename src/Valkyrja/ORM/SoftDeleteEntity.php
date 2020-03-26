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

namespace Valkyrja\ORM;

/**
 * Interface SoftDeleteEntity.
 *
 * @author Melech Mizrachi
 */
interface SoftDeleteEntity
{
    /**
     * Get the deleted field.
     *
     * @return string
     */
    public static function getDeletedField(): string;

    /**
     * Get the deleted at field.
     *
     * @return string
     */
    public static function getDeletedAtField(): string;

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
     * Get the deleted at field value.
     *
     * @return string|null
     */
    public function getDeletedAtFieldValue(): ?string;

    /**
     * Set the deleted at field value.
     *
     * @param string|null $deletedAt
     *
     * @return void
     */
    public function setDeletedAtFieldValue(string $deletedAt = null): void;
}
