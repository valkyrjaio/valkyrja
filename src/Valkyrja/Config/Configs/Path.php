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

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Config as Model;
use Valkyrja\Path\Enums\Config;

/**
 * Class Path.
 *
 * @author Melech Mizrachi
 */
class Path extends Model
{
    public array $patterns = [];

    /**
     * Path constructor.
     */
    public function __construct()
    {
        $this->patterns = (array) env(EnvKey::PATH_PATTERNS, array_merge(Config::PATTERNS, $this->patterns));
    }
}
