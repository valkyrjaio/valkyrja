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

namespace Valkyrja\Mail\Data\Contract;

interface AttachmentContract
{
    /**
     * Get the path.
     *
     * @return non-empty-string
     */
    public function getPath(): string;

    /**
     * Create a new instance with the given path.
     *
     * @param non-empty-string $path The path
     */
    public function withPath(string $path): static;

    /**
     * Determine if there is a name.
     */
    public function hasName(): bool;

    /**
     * Get the name.
     */
    public function getName(): string;

    /**
     * Create a new instance with the given name.
     */
    public function withName(string $name): static;
}
