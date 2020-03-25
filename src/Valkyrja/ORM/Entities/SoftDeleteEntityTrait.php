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

use function time;

/**
 * Trait SoftDeleteEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait SoftDeleteEntityTrait
{
    /**
     * Deleted flag.
     *
     * @var bool
     */
    protected bool $deleted = false;

    /**
     * Deleted at flag.
     *
     * @var string|null
     */
    protected ?string $deletedAt = null;

    /**
     * Get deleted flag.
     *
     * @return bool
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Set deleted flag.
     *
     * @param bool $deleted
     *
     * @return static
     */
    public function setDeleted(bool $deleted = true): self
    {
        $this->deleted = $deleted;

        $this->setDeletedAt((string) time());

        return $this;
    }

    /**
     * Get deleted at.
     *
     * @return string|null
     */
    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    /**
     * Set deleted at.
     *
     * @param string|null $deletedAt
     *
     * @return static
     */
    public function setDeletedAt(string $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt
            ? date('y-m-d H:i:s', strtotime($deletedAt))
            : null;

        return $this;
    }
}
