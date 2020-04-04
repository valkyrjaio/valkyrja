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

namespace Valkyrja\Session\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::ID,
        CKP::NAME,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::ID   => EnvKey::SESSION_ID,
        CKP::NAME => EnvKey::SESSION_NAME,
    ];

    /**
     * The optional id.
     *
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The optional name.
     *
     * @var string|null
     */
    public ?string $name = null;
}
