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

namespace Valkyrja\Container\Constants;

use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Annotation\Parsers\Parser as AnnotationParser;
use Valkyrja\Api\Apis\Api;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Auth\Managers\Auth as AuthManager;
use Valkyrja\Cache\Caches\Cache;
use Valkyrja\Client\Clients\GuzzleClient;
use Valkyrja\Console\Annotation\Annotators\Annotator as ConsoleAnnotator;
use Valkyrja\Console\Dispatchers\CacheableConsole;
use Valkyrja\Console\Inputs\Input;
use Valkyrja\Console\Kernels\Kernel as NativeConsoleKernel;
use Valkyrja\Console\Outputs\Output;
use Valkyrja\Container\Annotation\Annotators\Annotator as ContainerAnnotator;
use Valkyrja\Container\Dispatchers\CacheableContainer;
use Valkyrja\Crypt\Crypts\Crypt;
use Valkyrja\Crypt\Decrypters\SodiumDecrypter;
use Valkyrja\Crypt\Encrypters\SodiumEncrypter;
use Valkyrja\Dispatcher\Providers\ServiceProvider as DispatcherServiceProvider;
use Valkyrja\Event\Annotation\Annotators\Annotator as EventAnnotator;
use Valkyrja\Event\Dispatchers\CacheableEvents;
use Valkyrja\Event\Providers\ServiceProvider as EventServiceProvider;
use Valkyrja\Filesystem\Filesystems\Filesystem;
use Valkyrja\Http\Factories\ResponseFactory;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Responses\JsonResponse;
use Valkyrja\Http\Responses\RedirectResponse;
use Valkyrja\Http\Responses\Response;
use Valkyrja\HttpKernel\Kernels\Kernel;
use Valkyrja\Log\Providers\LoggerServiceProvider;
use Valkyrja\Mail\Managers\Mail;
use Valkyrja\Mail\Messages\PHPMailerMessage;
use Valkyrja\Notification\Managers\Notifier;
use Valkyrja\ORM\Entities\Entity;
use Valkyrja\ORM\Managers\ORM;
use Valkyrja\Path\Generators\PathGenerator;
use Valkyrja\Path\Parsers\PathParser;
use Valkyrja\Reflection\Reflectors\Reflector;
use Valkyrja\Routing\Annotation\Annotators\Annotator as RoutingAnnotator;
use Valkyrja\Routing\Dispatchers\CacheableRouter;
use Valkyrja\Session\Sessions\Session;
use Valkyrja\SMS\Managers\SMS;
use Valkyrja\SMS\Messages\NexmoMessage;
use Valkyrja\Support\Exception\Classes\ExceptionHandler;
use Valkyrja\Support\Model\Classes\Model;
use Valkyrja\Validation\Rules\ORM as ORMValidationRules;
use Valkyrja\Validation\Validators\Validator;
use Valkyrja\View\Views\View;

/**
 * Constant Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider
{
    public const API                  = Api::class;
    public const APP                  = Valkyrja::class;
    public const AUTH                 = AuthManager::class;
    public const ANNOTATOR            = Annotator::class;
    public const ANNOTATION_PARSER    = AnnotationParser::class;
    public const CACHE                = Cache::class;
    public const COMMAND_ANNOTATOR    = ConsoleAnnotator::class;
    public const CONSOLE              = CacheableConsole::class;
    public const CONSOLE_KERNEL       = NativeConsoleKernel::class;
    public const CONTAINER            = CacheableContainer::class;
    public const CONTAINER_ANNOTATOR  = ContainerAnnotator::class;
    public const DISPATCHER           = DispatcherServiceProvider::class;
    public const EVENT                = EventServiceProvider::class;
    public const EVENTS               = CacheableEvents::class;
    public const FILESYSTEM           = Filesystem::class;
    public const INPUT                = Input::class;
    public const OUTPUT               = Output::class;
    public const KERNEL               = Kernel::class;
    public const LISTENER_ANNOTATOR   = EventAnnotator::class;
    public const PATH_GENERATOR       = PathGenerator::class;
    public const PATH_PARSER          = PathParser::class;
    public const REQUEST              = Request::class;
    public const RESPONSE             = Response::class;
    public const JSON_RESPONSE        = JsonResponse::class;
    public const REDIRECT_RESPONSE    = RedirectResponse::class;
    public const RESPONSE_BUILDER     = ResponseFactory::class;
    public const REFLECTOR            = Reflector::class;
    public const ROUTER               = CacheableRouter::class;
    public const ROUTE_ANNOTATOR      = RoutingAnnotator::class;
    public const SESSION              = Session::class;
    public const VALIDATOR            = Validator::class;
    public const VALIDATION_ORM_RULES = ORMValidationRules::class;
    public const VIEW                 = View::class;
    public const CLIENT               = GuzzleClient::class;
    public const LOGGER               = LoggerServiceProvider::class;
    public const MAIL                 = Mail::class;
    public const MAIL_MESSAGE         = PHPMailerMessage::class;
    public const SMS                  = SMS::class;
    public const SMS_MESSAGE          = NexmoMessage::class;
    public const CRYPT                = Crypt::class;
    public const CRYPT_ENCRYPTER      = SodiumEncrypter::class;
    public const CRYPT_DECRYPTER      = SodiumDecrypter::class;
    public const NOTIFY               = Notifier::class;
    public const MODEL                = Model::class;
    public const ENTITY               = Entity::class;
    public const ENTITY_MANAGER       = ORM::class;
    public const EXCEPTION_HANDLER    = ExceptionHandler::class;
}
