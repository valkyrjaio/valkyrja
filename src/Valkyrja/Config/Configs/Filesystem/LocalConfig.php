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

namespace Valkyrja\Config\Configs\Filesystem;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class LocalConfig.
 *
 * @author Melech Mizrachi
 */
class LocalConfig extends Model
{
    /**
     * The adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The dir.
     *
     * @var string
     */
    public string $dir;

    /**
     * LocalConfig constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setAdapter();
        $this->setDir(storagePath('app'));
    }

    /**
     * Set the adapter.
     *
     * @param string $adapter [optional] The adapter
     *
     * @return void
     */
    protected function setAdapter(string $adapter = CKP::LOCAL): void
    {
        $this->adapter = (string) env(EnvKey::FILESYSTEM_LOCAL_ADAPTER, $adapter);
    }

    /**
     * Set the dir.
     *
     * @param string $dir [optional] The dir
     *
     * @return void
     */
    protected function setDir(string $dir = ''): void
    {
        $this->dir = (string) env(EnvKey::FILESYSTEM_LOCAL_DIR, $dir);
    }
}
