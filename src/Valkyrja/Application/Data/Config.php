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

namespace Valkyrja\Application\Data;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Provider;

readonly class Config
{
    /**
     * @param non-empty-string         $version
     * @param non-empty-string         $environment
     * @param bool                     $debugMode
     * @param non-empty-string         $timezone
     * @param class-string<Provider>[] $providers
     */
    public function __construct(
        public string $version = ApplicationContract::VERSION,
        public string $environment = 'production',
        public bool $debugMode = false,
        public string $timezone = 'UTC',
        public array $providers = [],
    ) {
    }
}
