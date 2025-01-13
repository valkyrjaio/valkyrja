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
 * Class InMemoryFile.
 *
 * @author Melech Mizrachi
 */
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
