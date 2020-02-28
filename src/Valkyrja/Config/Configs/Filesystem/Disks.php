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

use Valkyrja\Config\Models\Config as Model;

/**
 * Class Disks.
 *
 * @author Melech Mizrachi
 */
class Disks extends Model
{
    public Local $local;
    public S3 $s3;

    /**
     * Disks constructor.
     */
    public function __construct()
    {
        $this->setLocalDisk();
        $this->setS3Disk();
    }

    /**
     * Set the local disk.
     *
     * @return void
     */
    protected function setLocalDisk(): void
    {
        $this->local = new Local();
    }

    /**
     * Set the local disk.
     *
     * @return void
     */
    protected function setS3Disk(): void
    {
        $this->s3 = new S3();
    }
}
