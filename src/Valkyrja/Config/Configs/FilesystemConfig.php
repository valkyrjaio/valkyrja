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

use Valkyrja\Config\Configs\Filesystem\DisksConfig;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;
use Valkyrja\Filesystem\Enums\Config;

/**
 * Class FilesystemConfig.
 *
 * @author Melech Mizrachi
 */
class FilesystemConfig extends Model
{
    /**
     * The default adapter.
     *
     * @var string
     */
    public string $default;

    /**
     * The adapters.
     *
     * @var array
     */
    public array $adapters;

    /**
     * The disks.
     *
     * @var DisksConfig
     */
    public DisksConfig $disks;

    /**
     * FilesystemConfig constructor.
     */
    public function __construct()
    {
        $this->setDefault();
        $this->setAdapters();
        $this->setDisks();
    }

    /**
     * Set the default adapter.
     *
     * @param string $default [optional] The default adapter
     *
     * @return void
     */
    protected function setDefault(string $default = CKP::LOCAL): void
    {
        $this->default = (string) env(EnvKey::FILESYSTEM_DEFAULT, $default);
    }

    /**
     * Set the adapters.
     *
     * @param array $adapters [optional] The adapters
     *
     * @return void
     */
    protected function setAdapters(array $adapters = []): void
    {
        $this->adapters = (array) env(EnvKey::FILESYSTEM_ADAPTERS, array_merge(Config::ADAPTERS, $adapters));
    }

    /**
     * Set the disks.
     *
     * @param DisksConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setDisks(DisksConfig $config = null): void
    {
        $this->disks = $config ?? new DisksConfig();
    }
}
