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

namespace Valkyrja\Tests;

use Valkyrja\Annotation\Config\Config as Annotation;
use Valkyrja\Annotation\Constants\ConfigValue as AnnotationConfigValue;
use Valkyrja\Api\Config\Config as Api;
use Valkyrja\Api\Constants\ConfigValue as ApiConfigValue;
use Valkyrja\Application\Config\Config as App;
use Valkyrja\Application\Constants\ConfigValue as AppConfigValue;
use Valkyrja\Auth\Config\Config as Auth;
use Valkyrja\Auth\Constants\ConfigValue as AuthConfigValue;
use Valkyrja\Cache\Config\Config as Cache;
use Valkyrja\Cache\Constants\ConfigValue as CacheConfigValue;
use Valkyrja\Client\Config\Config as Client;
use Valkyrja\Client\Constants\ConfigValue as ClientConfigValue;
use Valkyrja\Config\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigValue;
use Valkyrja\Console\Config\Config as Console;
use Valkyrja\Console\Constants\ConfigValue as ConsoleConfigValue;
use Valkyrja\Container\Config\Config as Container;
use Valkyrja\Container\Constants\ConfigValue as ContainerConfigValue;
use Valkyrja\Crypt\Config\Config as Crypt;
use Valkyrja\Crypt\Constants\ConfigValue as CryptConfigValue;
use Valkyrja\Event\Config\Config as Event;
use Valkyrja\Event\Constants\ConfigValue as EventConfigValue;
use Valkyrja\Filesystem\Config\Config as Filesystem;
use Valkyrja\Filesystem\Constants\ConfigValue as FilesystemConfigValue;
use Valkyrja\Log\Config\Config as Log;
use Valkyrja\Log\Constants\ConfigValue as LogConfigValue;
use Valkyrja\Mail\Config\Config as Mail;
use Valkyrja\Mail\Constants\ConfigValue as MailConfigValue;
use Valkyrja\Notification\Config\Config as Notification;
use Valkyrja\Notification\Constants\ConfigValue as NotificationConfigValue;
use Valkyrja\ORM\Config\Config as ORM;
use Valkyrja\ORM\Constants\ConfigValue as ORMConfigValue;
use Valkyrja\Path\Config\Config as Path;
use Valkyrja\Path\Constants\ConfigValue as PathConfigValue;
use Valkyrja\Routing\Config\Config as Routing;
use Valkyrja\Routing\Constants\ConfigValue as RoutingConfigValue;
use Valkyrja\Session\Config\Config as Session;
use Valkyrja\Session\Constants\ConfigValue as SessionConfigValue;
use Valkyrja\SMS\Config\Config as SMS;
use Valkyrja\SMS\Constants\ConfigValue as SMSConfigValue;
use Valkyrja\Validation\Config\Config as Validation;
use Valkyrja\Validation\Constants\ConfigValue as ValidationConfigValue;
use Valkyrja\View\Config\Config as View;
use Valkyrja\View\Constants\ConfigValue as ViewConfigValue;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    public function __construct()
    {
        $this->annotation   = new Annotation(AnnotationConfigValue::$defaults, true);
        $this->api          = new Api(ApiConfigValue::$defaults, true);
        $this->app          = new App(AppConfigValue::$defaults, true);
        $this->auth         = new Auth(AuthConfigValue::$defaults, true);
        $this->cache        = new Cache(CacheConfigValue::$defaults, true);
        $this->client       = new Client(ClientConfigValue::$defaults, true);
        $this->console      = new Console(ConsoleConfigValue::$defaults, true);
        $this->container    = new Container(ContainerConfigValue::$defaults, true);
        $this->crypt        = new Crypt(CryptConfigValue::$defaults, true);
        $this->event        = new Event(EventConfigValue::$defaults, true);
        $this->filesystem   = new Filesystem(FilesystemConfigValue::$defaults, true);
        $this->log          = new Log(LogConfigValue::$defaults, true);
        $this->mail         = new Mail(MailConfigValue::$defaults, true);
        $this->notification = new Notification(NotificationConfigValue::$defaults, true);
        $this->orm          = new ORM(ORMConfigValue::$defaults, true);
        $this->path         = new Path(PathConfigValue::$defaults, true);
        $this->routing      = new Routing(RoutingConfigValue::$defaults, true);
        $this->session      = new Session(SessionConfigValue::$defaults, true);
        $this->sms          = new SMS(SMSConfigValue::$defaults, true);
        $this->validation   = new Validation(ValidationConfigValue::$defaults, true);
        $this->view         = new View(ViewConfigValue::$defaults, true);

        parent::__construct(ConfigValue::$defaults, true);
    }
}
