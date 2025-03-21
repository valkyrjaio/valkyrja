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

namespace Valkyrja\Annotation;

use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Annotation\Constant\ConfigName;
use Valkyrja\Annotation\Constant\EnvName;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class DataConfig extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ALIASES => EnvName::ALIASES,
        ConfigName::MAP     => EnvName::MAP,
    ];

    /**
     * @param array<string, class-string>[]           $aliases
     * @param array<string, class-string<Annotation>> $map
     */
    public function __construct(
        public array $aliases = [],
        public array $map = [],
    ) {
    }
}
