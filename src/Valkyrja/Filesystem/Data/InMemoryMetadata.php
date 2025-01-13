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

use function get_object_vars;

/**
 * Class InMemoryMetadata.
 *
 * @author Melech Mizrachi
 */
class InMemoryMetadata
{
    public function __construct(
        public string|null $mimetype = null,
        public int|null $size = 0,
        public string|null $visibility = null,
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
