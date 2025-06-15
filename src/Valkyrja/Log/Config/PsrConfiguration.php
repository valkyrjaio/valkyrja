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

use Valkyrja\Log\Adapter\PsrAdapter;
use Valkyrja\Log\Constant\ConfigName;
use Valkyrja\Support\Directory;

/**
 * Class PsrConfiguration.
 *
 * @author Melech Mizrachi
 */
class PsrConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'LOG_PSR_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'LOG_PSR_DRIVER_CLASS',
        ConfigName::NAME          => 'LOG_PSR_NAME',
        ConfigName::FILE_PATH     => 'LOG_PSR_FILE_PATH',
    ];

    public function __construct(
        public string $name = 'application-log',
        public string $filePath = '',
    ) {
        parent::__construct(
            adapterClass: PsrAdapter::class,
        );

        if ($this->filePath === '') {
            $this->filePath = Directory::storagePath('logs');
        }
    }
}
