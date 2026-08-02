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

class InMemoryFile
{
    public function __construct(
        public string $name,
        public string $contents = '',
        public InMemoryMetadata $metadata = new InMemoryMetadata(),
        public int $timestamp = 0
    ) {
    }
}
