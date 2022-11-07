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

namespace Valkyrja\Application\Config;

use App\Http\Kernel;
use Valkyrja\Application\Application;
use Valkyrja\Application\Config\Config as Model;
use Valkyrja\Application\Constants\ConfigValue;

/**
 * Class App.
 */
class App extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->env              = 'production';
        $this->debug            = false;
        $this->url              = 'localhost';
        $this->timezone         = 'UTC';
        $this->version          = Application::VERSION;
        $this->key              = 'some_secret_app_key';
        $this->exceptionHandler = ConfigValue::EXCEPTION_HANDLER;
        $this->httpKernel       = Kernel::class;
        $this->providers        = array_merge(ConfigValue::PROVIDERS, []);
    }
}
