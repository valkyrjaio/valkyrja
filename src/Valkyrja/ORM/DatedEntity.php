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
 * Interface DatedEntity.
 *
 * @author Melech Mizrachi
 */
interface DatedEntity extends Entity
{
    /**
     * Get the created at field.
     *
     * @return string
     */
    public static function getCreatedAtField(): string;

    /**
     * Get the updated at field.
     *
     * @return string
     */
    public static function getUpdatedAtField(): string;

    /**
     * Get the created at field value.
     *
     * @return string
     */
    public function getCreatedAtFieldValue(): string;

    /**
     * Set the created at field value.
     *
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAtFieldValue(string $createdAt): void;

    /**
     * Get the updated at field value.
     *
     * @return string
     */
    public function getUpdatedAtFieldValue(): string;

    /**
     * Set the updated at field value.
     *
     * @param string $updatedAt
     *
     * @return void
     */
    public function setUpdatedAtFieldValue(string $updatedAt): void;
}
