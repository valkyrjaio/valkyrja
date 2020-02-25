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

namespace Valkyrja\Filesystem;

use League\Flysystem\Adapter\Local;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;

/**
 * Abstract Class FlysystemLocal.
 *
 * @author Melech Mizrachi
 */
class FlysystemLocal extends FlysystemAdapter
{
    /**
     * Make a new adapter instance.
     *
     * @return static
     */
    public static function make(): self
    {
        $config = config()[CKP::FILESYSTEM][CKP::DISKS][CKP::LOCAL];

        return new static(
            new Local($config[CKP::DIR])
        );
    }
}
