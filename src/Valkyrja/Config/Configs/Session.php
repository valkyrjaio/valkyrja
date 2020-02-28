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
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Session.
 *
 * @author Melech Mizrachi
 */
class Session extends Model
{
    public string $id   = '';
    public string $name = '';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        $this->id   = (string) env(EnvKey::SESSION_ID, $this->id);
        $this->name = (string) env(EnvKey::SESSION_NAME, $this->name);
    }
}
