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

namespace Valkyrja\Container\Enums;

use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Cache\Caches\Cache;
use Valkyrja\Client\Clients\GuzzleClient;
use Valkyrja\Console\Annotation\Annotators\CommandAnnotator;
use Valkyrja\Console\Dispatchers\Console;
use Valkyrja\Console\Inputs\Input;
use Valkyrja\Console\Kernels\Kernel as NativeConsoleKernel;
use Valkyrja\Console\Outputs\Output;
use Valkyrja\Container\Annotation\Annotators\ContainerAnnotator;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Crypt\Crypts\Crypt;
use Valkyrja\Crypt\Decrypters\SodiumDecrypter;
use Valkyrja\Crypt\Encrypters\SodiumEncrypter;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\Env\Env;
use Valkyrja\Event\Annotation\Annotators\ListenerAnnotator;
use Valkyrja\Event\Dispatchers\Events;
use Valkyrja\Exception\Handlers\ExceptionHandler;
use Valkyrja\Filesystem\Filesystems\Filesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Factories\ResponseFactory;
use Valkyrja\Http\Kernels\Kernel;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Responses\JsonResponse;
use Valkyrja\Http\Responses\RedirectResponse;
use Valkyrja\Http\Responses\Response;
use Valkyrja\Logging\Providers\LoggerServiceProvider;
use Valkyrja\Mail\Mailers\PHPMailerMail;
use Valkyrja\Model\Models\Model;
use Valkyrja\ORM\Entities\Entity;
use Valkyrja\ORM\EntityManagers\EntityManager;
use Valkyrja\Path\Generators\PathGenerator;
use Valkyrja\Path\Parsers\PathParser;
use Valkyrja\Reflection\Reflectors\Reflector;
use Valkyrja\Routing\Annotation\Annotators\RouteAnnotator;
use Valkyrja\Routing\Dispatchers\Router;
use Valkyrja\Session\Sessions\Session;
use Valkyrja\View\Views\View;

/**
 * Enum Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider extends Enum
{
    public const APP                 = Valkyrja::class;
    public const ANNOTATOR           = Annotator::class;
    public const CACHE               = Cache::class;
    public const COMMAND_ANNOTATOR   = CommandAnnotator::class;
    public const CONSOLE             = Console::class;
    public const CONSOLE_KERNEL      = NativeConsoleKernel::class;
    public const CONTAINER           = Container::class;
    public const CONTAINER_ANNOTATOR = ContainerAnnotator::class;
    public const DISPATCHER          = Dispatcher::class;
    public const ENV                 = Env::class;
    public const EVENTS              = Events::class;
    public const FILESYSTEM          = Filesystem::class;
    public const INPUT               = Input::class;
    public const OUTPUT              = Output::class;
    public const KERNEL              = Kernel::class;
    public const LISTENER_ANNOTATOR  = ListenerAnnotator::class;
    public const PATH_GENERATOR      = PathGenerator::class;
    public const PATH_PARSER         = PathParser::class;
    public const REQUEST             = Request::class;
    public const RESPONSE            = Response::class;
    public const JSON_RESPONSE       = JsonResponse::class;
    public const REDIRECT_RESPONSE   = RedirectResponse::class;
    public const RESPONSE_BUILDER    = ResponseFactory::class;
    public const REFLECTOR           = Reflector::class;
    public const ROUTER              = Router::class;
    public const ROUTE_ANNOTATOR     = RouteAnnotator::class;
    public const SESSION             = Session::class;
    public const VIEW                = View::class;
    public const CLIENT              = GuzzleClient::class;
    public const LOGGER              = LoggerServiceProvider::class;
    public const MAIL                = PHPMailerMail::class;
    public const CRYPT               = Crypt::class;
    public const CRYPT_ENCRYPTER     = SodiumEncrypter::class;
    public const CRYPT_DECRYPTER     = SodiumDecrypter::class;
    public const MODEL               = Model::class;
    public const ENTITY              = Entity::class;
    public const ENTITY_MANAGER      = EntityManager::class;
    public const EXCEPTION_HANDLER   = ExceptionHandler::class;
    public const HTTP_EXCEPTION      = HttpException::class;
}
