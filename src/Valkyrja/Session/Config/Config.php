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
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

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
        CKP::ADAPTER,
        CKP::ADAPTERS,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::ID       => EnvKey::SESSION_ID,
        CKP::NAME     => EnvKey::SESSION_NAME,
        CKP::ADAPTER  => EnvKey::SESSION_ADAPTER,
        CKP::ADAPTERS => EnvKey::SESSION_ADAPTERS,
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

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The adapters.
     *
     * @var array
     */
    public array $adapters;
}
