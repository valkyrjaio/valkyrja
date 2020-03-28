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

namespace Valkyrja\Config\Configs\Filesystem;

use Valkyrja\Config\Models\Model;

/**
 * Class Disks.
 *
 * @author Melech Mizrachi
 */
class Disks extends Model
{
    /**
     * The local disk.
     *
     * @var Local
     */
    public Local $local;

    /**
     * The s3 disk.
     *
     * @var S3
     */
    public S3 $s3;

    /**
     * Disks constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setLocalDisk();
        $this->setS3Disk();
    }

    /**
     * Set the local disk.
     *
     * @param Local|null $config [optional] The config
     *
     * @return void
     */
    protected function setLocalDisk(Local $config = null): void
    {
        $this->local = $config ?? new Local();
    }

    /**
     * Set the local disk.
     *
     * @param S3|null $config [optional] The config
     *
     * @return void
     */
    protected function setS3Disk(S3 $config = null): void
    {
        $this->s3 = $config ?? new S3();
    }
}
