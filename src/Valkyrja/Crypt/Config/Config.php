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

namespace Valkyrja\Crypt\Config;

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
        CKP::KEY,
        CKP::KEY_PATH,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::KEY      => EnvKey::CRYPT_KEY,
        CKP::KEY_PATH => EnvKey::CRYPT_KEY_PATH,
    ];

    /**
     * The key.
     *
     * @var string
     */
    public string $key;

    /**
     * The optional key path (for a key on disk).
     *
     * @var string|null
     */
    public ?string $keyPath = null;
}
