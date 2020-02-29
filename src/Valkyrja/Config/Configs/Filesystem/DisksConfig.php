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

use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class DisksConfig.
 *
 * @author Melech Mizrachi
 */
class DisksConfig extends Model
{
    /**
     * The local disk.
     *
     * @var LocalConfig
     */
    public LocalConfig $local;

    /**
     * The s3 disk.
     *
     * @var S3Config
     */
    public S3Config $s3;

    /**
     * DisksConfig constructor.
     */
    public function __construct()
    {
        $this->setLocalDisk();
        $this->setS3Disk();
    }

    /**
     * Set the local disk.
     *
     * @param LocalConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setLocalDisk(LocalConfig $config = null): void
    {
        $this->local = $config ?? new LocalConfig();
    }

    /**
     * Set the local disk.
     *
     * @param S3Config|null $config [optional] The config
     *
     * @return void
     */
    protected function setS3Disk(S3Config $config = null): void
    {
        $this->s3 = $config ?? new S3Config();
    }
}
