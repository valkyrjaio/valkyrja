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
    /**
     * The optional id.
     *
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The optional name.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * SessionConfig constructor.
     */
    public function __construct()
    {
        $this->setId();
        $this->setName();
    }

    /**
     * Set the optional id.
     *
     * @param string|null $id [optional] The optional id
     *
     * @return void
     */
    protected function setId(string $id = null): void
    {
        $this->id = env(EnvKey::SESSION_ID, $id);
    }

    /**
     * Set the optional name.
     *
     * @param string|null $name [optional] The optional name
     *
     * @return void
     */
    protected function setName(string $name = null): void
    {
        $this->name = env(EnvKey::SESSION_NAME, $name);
    }
}
