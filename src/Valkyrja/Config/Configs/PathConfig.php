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
use Valkyrja\Config\Models\ConfigModel as Model;
use Valkyrja\Path\Enums\Config;

/**
 * Class PathConfig.
 *
 * @author Melech Mizrachi
 */
class PathConfig extends Model
{
    /**
     * The patterns.
     *
     * @var array
     */
    public array $patterns;

    /**
     * PathConfig constructor.
     */
    public function __construct()
    {
        $this->setPatterns();
    }

    /**
     * Set the patterns.
     *
     * @param array $patterns [optional] The patterns
     *
     * @return void
     */
    protected function setPatterns(array $patterns = []): void
    {
        $this->patterns = (array) env(EnvKey::PATH_PATTERNS, array_merge(Config::PATTERNS, $patterns));
    }
}