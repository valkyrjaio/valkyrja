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

use Psr\Log\LoggerInterface;
use Valkyrja\Annotation\Annotator;
use Valkyrja\Application\Application;
use Valkyrja\Cache\Cache;
use Valkyrja\Client\Client;
use Valkyrja\Console\Annotation\CommandAnnotator;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Console\Output;
use Valkyrja\Container\Annotation\ContainerAnnotator;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Decrypter;
use Valkyrja\Crypt\Encrypter;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Enum\Enums\Enum;
use Valkyrja\Env\Env;
use Valkyrja\Event\Annotation\ListenerAnnotator;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Logging\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\Model\Model;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\EntityManager;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Routing\Annotation\RouteAnnotator;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

/**
 * Enum Contract.
 *
 * @author Melech Mizrachi
 */
final class Contract extends Enum
{
    public const APP                 = Application::class;
    public const ANNOTATOR           = Annotator::class;
    public const COMMAND_ANNOTATOR   = CommandAnnotator::class;
    public const CONFIG              = 'config';
    public const CACHE               = Cache::class;
    public const CONSOLE             = Console::class;
    public const CONSOLE_KERNEL      = ConsoleKernel::class;
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
    public const RESPONSE_FACTORY    = ResponseFactory::class;
    public const REFLECTOR           = Reflector::class;
    public const ROUTER              = Router::class;
    public const ROUTE_ANNOTATOR     = RouteAnnotator::class;
    public const SESSION             = Session::class;
    public const VIEW                = View::class;
    public const CLIENT              = Client::class;
    public const LOGGER_INTERFACE    = LoggerInterface::class;
    public const LOGGER              = Logger::class;
    public const MAIL                = Mail::class;
    public const CRYPT               = Crypt::class;
    public const ENCRYPTER           = Encrypter::class;
    public const DECRYPTER           = Decrypter::class;
    public const MODEL               = Model::class;
    public const ENTITY              = Entity::class;
    public const ENTITY_MANAGER      = EntityManager::class;
    public const QUERY               = Query::class;
    public const QUERY_BUILDER       = QueryBuilder::class;
    public const REPOSITORY          = Repository::class;
    public const EXCEPTION_HANDLER   = ExceptionHandler::class;
    public const HTTP_EXCEPTION      = HttpException::class;
}
