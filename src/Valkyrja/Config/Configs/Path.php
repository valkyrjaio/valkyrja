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
use Valkyrja\Config\Models\Model;
use Valkyrja\Path\Enums\Config;

use function env;

/**
 * Class Path
 *
 * @author Melech Mizrachi
 */
class Path extends Model
{
    /**
     * The patterns.
     *
     * @var array
     */
    public array $patterns;

    /**
     * Path constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setPatterns();
    }

    /**
     * Set the patterns.
     *
     * @param array $patterns [optional] The patterns
     *
     * @return void
     */
    protected function setPatterns(array $patterns = Config::PATTERNS): void
    {
        $this->patterns = (array) env(EnvKey::PATH_PATTERNS, $patterns);
    }
}
