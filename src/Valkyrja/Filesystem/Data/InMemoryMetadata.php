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

namespace Valkyrja\Filesystem\Data;

/**
 * @psalm-type InMemoryMetadataAsArray array{mimetype: string|null, size: int|null, visibility: string|null}
 *
 * @phpstan-type InMemoryMetadataAsArray array{mimetype: string|null, size: int|null, visibility: string|null}
 */
class InMemoryMetadata
{
    public function __construct(
        public string|null $mimetype = null,
        public int|null $size = 0,
        public string|null $visibility = null,
    ) {
    }

    /**
     * @return InMemoryMetadataAsArray
     */
    public function toArray(): array
    {
        return [
            'mimetype'   => $this->mimetype,
            'size'       => $this->size,
            'visibility' => $this->visibility,
        ];
    }
}
