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

namespace Valkyrja\Log\Config;

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
        CKP::NAME,
        CKP::FILE_PATH,
        CKP::ADAPTER,
        CKP::ADAPTERS,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::NAME      => EnvKey::LOG_NAME,
        CKP::FILE_PATH => EnvKey::LOG_FILE_PATH,
        CKP::ADAPTER   => EnvKey::LOG_ADAPTER,
        CKP::ADAPTERS  => EnvKey::LOG_ADAPTERS,
    ];

    /**
     * The log name.
     *
     * @var string
     */
    public string $name;

    /**
     * The file path.
     *
     * @var string
     */
    public string $filePath;

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
