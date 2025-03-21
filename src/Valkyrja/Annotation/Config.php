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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        CKP::ENABLED => EnvKey::ANNOTATIONS_ENABLED,
        CKP::MAP     => EnvKey::ANNOTATIONS_MAP,
        CKP::ALIASES => EnvKey::ANNOTATIONS_ALIASES,
    ];

    /**
     * Flag for whether annotations are enabled.
     *
     * @var bool
     */
    public bool $enabled;

    /**
     * The annotations map.
     *
     * @example
     * <code>
     *      [
     *         'Annotation' => Annotation::class,
     *      ]
     * </code>
     *
     * @var array<string, string>
     */
    public array $map;

    /**
     * The annotation aliases.
     *
     * @example
     * <code>
     *      [
     *         'Word' => WordEnum::class,
     *      ]
     * </code>
     * Then we can do:
     * <code>
     *
     * Annotation("name" : "Word::VALUE")
     * </code>
     *
     * @var array<string, string>
     */
    public array $aliases;
}
