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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Config\Constant\EnvKey;
use Valkyrja\Log\Config\Config as Model;
use Valkyrja\Log\Constants\ConfigValue;

use function Valkyrja\env;
use function Valkyrja\storagePath;

/**
 * Class Log.
 */
class Log extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->loggers = [
            CKP::PSR => [
                CKP::ADAPTER   => null,
                CKP::DRIVER    => null,
                CKP::NAME      => env(EnvKey::LOG_NAME, 'application-log'),
                CKP::FILE_PATH => env(EnvKey::LOG_FILE_PATH, storagePath('logs')),
            ],
        ];
    }
}
