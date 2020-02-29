<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class LoggingConfig.
 *
 * @author Melech Mizrachi
 */
class LoggingConfig extends Model
{
    public string $name     = 'ApplicationLog';
    public string $filePath = '';

    /**
     * LoggingConfig constructor.
     */
    public function __construct()
    {
        $this->name     = env(EnvKey::LOG_NAME, $this->name);
        $this->filePath = (string) env(EnvKey::LOG_FILE_PATH, storagePath('logs/valkyrja.log'));
    }
}
