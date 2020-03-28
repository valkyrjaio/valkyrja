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

use Valkyrja\Config\Configs\Filesystem\Disks;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;
use Valkyrja\Filesystem\Enums\Config;

use function env;

/**
 * Class Filesystem
 *
 * @author Melech Mizrachi
 */
class Filesystem extends Model
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
     * @var Disks
     */
    public Disks $disks;

    /**
     * Filesystem constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

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
    protected function setAdapters(array $adapters = Config::ADAPTERS): void
    {
        $this->adapters = (array) env(EnvKey::FILESYSTEM_ADAPTERS, $adapters);
    }

    /**
     * Set the disks.
     *
     * @param Disks|null $config [optional] The config
     *
     * @return void
     */
    protected function setDisks(Disks $config = null): void
    {
        $this->disks = $config ?? new Disks();
    }
}
