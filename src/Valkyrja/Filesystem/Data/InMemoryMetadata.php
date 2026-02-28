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

use Valkyrja\Filesystem\Enum\Visibility;

/**
 * @psalm-type InMemoryMetadataAsArray array{mimetype: string, size: int, visibility: string}
 *
 * @phpstan-type InMemoryMetadataAsArray array{mimetype: string, size: int, visibility: string}
 */
class InMemoryMetadata
{
    public function __construct(
        public string $mimetype = '',
        public int $size = 0,
        public Visibility $visibility = Visibility::PUBLIC,
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
            'visibility' => $this->visibility->value,
        ];
    }
}
