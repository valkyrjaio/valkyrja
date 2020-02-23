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
use Valkyrja\Client\GuzzleClient;
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
use Valkyrja\Event\Dispatchers\NativeEvents;
use Valkyrja\Exception\NativeExceptionHandler;
use Valkyrja\Filesystem\FlyFilesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Factories\ResponseFactory;
use Valkyrja\Http\Kernels\Kernel;
use Valkyrja\Http\Requests\Request;
use Valkyrja\Http\Responses\JsonResponse;
use Valkyrja\Http\Responses\RedirectResponse;
use Valkyrja\Http\Responses\Response;
use Valkyrja\Logger\Providers\LoggerServiceProvider;
use Valkyrja\Mail\PHPMailerMail;
use Valkyrja\Model\NativeModel;
use Valkyrja\ORM\Entities\Entity;
use Valkyrja\ORM\EntityManagers\PDOEntityManager;
use Valkyrja\ORM\Queries\PDOQuery;
use Valkyrja\ORM\QueryBuilders\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\Repository;
use Valkyrja\Path\NativePathGenerator;
use Valkyrja\Path\NativePathParser;
use Valkyrja\Routing\Annotation\Annotators\RouteAnnotator;
use Valkyrja\Routing\Dispatchers\Router;
use Valkyrja\Session\NativeSession;
use Valkyrja\View\PhpView;

/**
 * Enum Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider extends Enum
{
    public const APP                 = Valkyrja::class;
    public const ANNOTATOR           = Annotator::class;
    public const COMMAND_ANNOTATOR   = CommandAnnotator::class;
    public const CONSOLE             = Console::class;
    public const CONSOLE_KERNEL      = NativeConsoleKernel::class;
    public const CONTAINER           = Container::class;
    public const CONTAINER_ANNOTATOR = ContainerAnnotator::class;
    public const DISPATCHER          = Dispatcher::class;
    public const ENV                 = Env::class;
    public const EVENTS              = NativeEvents::class;
    public const FILESYSTEM          = FlyFilesystem::class;
    public const INPUT               = Input::class;
    public const OUTPUT              = Output::class;
    public const KERNEL              = Kernel::class;
    public const LISTENER_ANNOTATOR  = ListenerAnnotator::class;
    public const PATH_GENERATOR      = NativePathGenerator::class;
    public const PATH_PARSER         = NativePathParser::class;
    public const REQUEST             = Request::class;
    public const RESPONSE            = Response::class;
    public const JSON_RESPONSE       = JsonResponse::class;
    public const REDIRECT_RESPONSE   = RedirectResponse::class;
    public const RESPONSE_BUILDER    = ResponseFactory::class;
    public const ROUTER              = Router::class;
    public const ROUTE_ANNOTATOR     = RouteAnnotator::class;
    public const SESSION             = NativeSession::class;
    public const VIEW                = PhpView::class;
    public const CLIENT              = GuzzleClient::class;
    public const LOGGER              = LoggerServiceProvider::class;
    public const MAIL                = PHPMailerMail::class;
    public const CRYPT               = Crypt::class;
    public const CRYPT_ENCRYPTER     = SodiumEncrypter::class;
    public const CRYPT_DECRYPTER     = SodiumDecrypter::class;
    public const MODEL               = NativeModel::class;
    public const ENTITY              = Entity::class;
    public const ENTITY_MANAGER      = PDOEntityManager::class;
    public const QUERY               = PDOQuery::class;
    public const QUERY_BUILDER       = SqlQueryBuilder::class;
    public const REPOSITORY          = Repository::class;
    public const EXCEPTION_HANDLER   = NativeExceptionHandler::class;
    public const HTTP_EXCEPTION      = HttpException::class;
}
