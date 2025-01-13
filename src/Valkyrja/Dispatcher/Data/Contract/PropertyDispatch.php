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

namespace Valkyrja\Dispatcher\Data\Contract;

/**
 * Interface PropertyDispatch.
 *
 * @author Melech Mizrachi
 */
interface PropertyDispatch extends ClassDispatch
{
    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty(): string;

    /**
     * Create a new dispatch with the specified property.
     *
     * @param string $property The property
     *
     * @return static
     */
    public function withProperty(string $property): static;

    /**
     * Determine whether this is a static property.
     *
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * Create a new dispatch with whether this is a static property.
     *
     * @param bool $isStatic The static flag
     *
     * @return $this
     */
    public function withIsStatic(bool $isStatic): static;
}
