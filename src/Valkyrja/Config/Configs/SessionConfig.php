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

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class SessionConfig.
 *
 * @author Melech Mizrachi
 */
class SessionConfig extends Model
{
    public ?string $id   = null;
    public ?string $name = null;

    /**
     * SessionConfig constructor.
     */
    public function __construct()
    {
        $this->id   = env(EnvKey::SESSION_ID, $this->id);
        $this->name = env(EnvKey::SESSION_NAME, $this->name);
    }
}
