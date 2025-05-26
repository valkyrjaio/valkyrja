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

namespace Valkyrja\Config\Config;

use Valkyrja\Application\Config\App;
use Valkyrja\Broadcast\Config\Broadcast;
use Valkyrja\Cache\Config\Cache;
use Valkyrja\Client\Config\Client;
use Valkyrja\Config\Config\Config as Model;
use Valkyrja\Console\Config\Console;
use Valkyrja\Container\Config\Container;
use Valkyrja\Crypt\Config\Crypt;
use Valkyrja\Event\Config\Event;
use Valkyrja\Filesystem\Config\Filesystem;
use Valkyrja\Http\Config\Http;
use Valkyrja\Http\Routing\Config\Routing;
use Valkyrja\Jwt\Config\Jwt;
use Valkyrja\Log\Config\Log;
use Valkyrja\Mail\Config\Mail;
use Valkyrja\Orm\Config\Orm;
use Valkyrja\Path\Config\Path;
use Valkyrja\Session\Config\Session;
use Valkyrja\Sms\Config\Sms;
use Valkyrja\View\Config\View;

use function Valkyrja\cachePath;

/**
 * Class Valkyrja.
 *
 * @author Melech Mizrachi
 */
class Valkyrja extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        /** @var array<string, array<string, mixed>>|null $properties */
        $this->app        = new App($properties['app'] ?? null, true);
        $this->broadcast  = new Broadcast($properties['broadcast'] ?? null, true);
        $this->cache      = new Cache($properties['cache'] ?? null, true);
        $this->client     = new Client($properties['client'] ?? null, true);
        $this->console    = new Console($properties['console'] ?? null, true);
        $this->container  = new Container($properties['container'] ?? null, true);
        $this->crypt      = new Crypt($properties['crypt'] ?? null, true);
        $this->event      = new Event($properties['event'] ?? null, true);
        $this->filesystem = new Filesystem($properties['filesystem'] ?? null, true);
        $this->http       = new Http($properties['http'] ?? null, true);
        $this->jwt        = new Jwt($properties['jwt'] ?? null, true);
        $this->log        = new Log($properties['log'] ?? null, true);
        $this->mail       = new Mail($properties['mail'] ?? null, true);
        $this->orm        = new Orm($properties['orm'] ?? null, true);
        $this->path       = new Path($properties['path'] ?? null, true);
        $this->routing    = new Routing($properties['routing'] ?? null, true);
        $this->session    = new Session($properties['session'] ?? null, true);
        $this->sms        = new Sms($properties['sms'] ?? null, true);
        $this->view       = new View($properties['view'] ?? null, true);

        $this->providers     = [];
        $this->cacheFilePath = cachePath('config.php');
        $this->useCache      = false;
    }
}
