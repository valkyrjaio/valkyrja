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

namespace Valkyrja\Application\Data\Contract;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;

interface ConfigContract
{
    /** @var non-empty-string */
    public string $namespace {
        get;
    }
    /** @var non-empty-string */
    public string $dir {
        get;
    }
    /** @var non-empty-string */
    public string $version {
        get;
    }
    /** @var non-empty-string */
    public string $environment {
        get;
    }
    public bool $debugMode {
        get;
    }
    /** @var non-empty-string */
    public string $timezone {
        get;
    }
    /** @var non-empty-string */
    public string $key {
        get;
    }
    /** @var non-empty-string */
    public string $dataPath {
        get;
    }
    /** @var non-empty-string */
    public string $dataNamespace {
        get;
    }
    /** @var ComponentProviderContract[] */
    public array $providers {
        get;
    }
    /** @var array<callable(ApplicationContract):void> */
    public array $callbacks {
        get;
    }
}
