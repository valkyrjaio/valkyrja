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

use Valkyrja\Annotation\Constant\Alias;
use Valkyrja\Annotation\Constant\AliasClass;
use Valkyrja\Annotation\Constant\AnnotationClass;
use Valkyrja\Annotation\Constant\AnnotationName;
use Valkyrja\Annotation\Constant\ConfigName;
use Valkyrja\Annotation\Constant\EnvName;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Config\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
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
     * @param array<class-string|string, class-string> $aliases A list of aliases
     * @param array<string, class-string<Annotation>>  $map     A list of mappings
     */
    public function __construct(
        public array $aliases = [],
        public array $map = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
        // Specifically done this way to allow for previously set aliases overwrite the default ones here
        $this->aliases = array_merge(
            [
                Alias::REQUEST_METHOD => AliasClass::REQUEST_METHOD,
                Alias::STATUS_CODE    => AliasClass::STATUS_CODE,
            ],
            $this->aliases
        );

        // Specifically done this way to allow for previously set mappings overwrite the default ones here
        $this->map = array_merge(
            [
                AnnotationName::COMMAND => AnnotationClass::COMMAND,
            ],
            $this->map
        );
    }
}
