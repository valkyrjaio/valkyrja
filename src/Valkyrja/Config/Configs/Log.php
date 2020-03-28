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

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;

use function Valkyrja\env;
use function Valkyrja\storagePath;

/**
 * Class Logging
 *
 * @author Melech Mizrachi
 */
class Log extends Model
{
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
     * Logging constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setName();
        $this->setFilePath(storagePath('logs'));
    }

    /**
     * Set the log name.
     *
     * @param string $name [optional] The log name
     *
     * @return void
     */
    protected function setName(string $name = 'ApplicationLog'): void
    {
        $this->name = (string) env(EnvKey::LOG_NAME, $name);
    }

    /**
     * Set the file path.
     *
     * @param string $name [optional] The file path
     *
     * @return void
     */
    protected function setFilePath(string $filePath = ''): void
    {
        $this->filePath = (string) env(EnvKey::LOG_FILE_PATH, $filePath);
    }
}
