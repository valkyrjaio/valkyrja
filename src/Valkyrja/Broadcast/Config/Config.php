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

namespace Valkyrja\Broadcast\Config;

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
        CKP::ADAPTER,
        CKP::ADAPTERS,
        CKP::MESSAGE,
        CKP::MESSAGES,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::ADAPTER  => EnvKey::BROADCAST_ADAPTER,
        CKP::ADAPTERS => EnvKey::BROADCAST_ADAPTERS,
        CKP::MESSAGE  => EnvKey::BROADCAST_ADAPTER,
        CKP::MESSAGES => EnvKey::BROADCAST_ADAPTERS,
    ];

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

    /**
     * The default message.
     *
     * @var string
     */
    public string $message;

    /**
     * The messages.
     *
     * @var array
     */
    public array $messages;
}
