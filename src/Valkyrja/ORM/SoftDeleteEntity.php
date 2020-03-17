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
interface SoftDeleteEntity extends Entity
{
    /**
     * Get deleted flag.
     *
     * @return bool
     */
    public function getDeleted(): bool;

    /**
     * Set deleted flag.
     *
     * @param bool $deleted
     *
     * @return $this
     */
    public function setDeleted(bool $deleted = true): self;

    /**
     * Get deleted at.
     *
     * @return string|null
     */
    public function getDeletedAt(): ?string;

    /**
     * Set deleted at.
     *
     * @param string|null $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(string $deletedAt = null): self;
}
