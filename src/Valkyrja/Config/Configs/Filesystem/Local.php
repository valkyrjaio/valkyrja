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
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Local.
 *
 * @author Melech Mizrachi
 */
class Local extends Model
{
    public string $adapter = CKP::LOCAL;
    public string $dir     = '';

    /**
     * Local constructor.
     */
    public function __construct()
    {
        $this->adapter = (string) env(EnvKey::FILESYSTEM_LOCAL_ADAPTER, $this->adapter);
        $this->dir     = (string) env(EnvKey::FILESYSTEM_LOCAL_DIR, storagePath('app'));
    }
}
