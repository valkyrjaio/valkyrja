<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
