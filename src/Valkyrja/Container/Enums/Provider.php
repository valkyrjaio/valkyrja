<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Enums;

use Valkyrja\Annotation\Annotations\Annotations;
use Valkyrja\Annotation\Parsers\AnnotationsParser;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Client\GuzzleClient;
use Valkyrja\Console\Annotation\Annotations\CommandAnnotations;
use Valkyrja\Console\Dispatchers\Console;
use Valkyrja\Console\Inputs\Input;
use Valkyrja\Console\Kernels\Kernel as NativeConsoleKernel;
use Valkyrja\Console\Outputs\Output;
use Valkyrja\Console\Outputs\OutputFormatter;
use Valkyrja\Container\Annotation\Annotations\ContainerAnnotations;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Crypt\Crypts\Crypt;
use Valkyrja\Crypt\Decrypters\SodiumDecrypter;
use Valkyrja\Crypt\Encrypters\SodiumEncrypter;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Enum\Enum;
use Valkyrja\Env\Env;
use Valkyrja\Event\Annotation\Annotations\ListenerAnnotations;
use Valkyrja\Event\Dispatchers\NativeEvents;
use Valkyrja\Exception\NativeExceptionHandler;
use Valkyrja\Filesystem\FlyFilesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\NativeJsonResponse;
use Valkyrja\Http\NativeKernel;
use Valkyrja\Http\NativeRedirectResponse;
use Valkyrja\Http\NativeRequest;
use Valkyrja\Http\NativeResponse;
use Valkyrja\Http\NativeResponseBuilder;
use Valkyrja\Logger\Providers\LoggerServiceProvider;
use Valkyrja\Mail\PHPMailerMail;
use Valkyrja\Model\NativeModel;
use Valkyrja\ORM\Entities\Entity;
use Valkyrja\ORM\EntityManagers\PDOEntityManager;
use Valkyrja\ORM\Queries\PDOQuery;
use Valkyrja\ORM\QueryBuilder\SqlQueryBuilder;
use Valkyrja\ORM\Repositories\Repository;
use Valkyrja\Path\NativePathGenerator;
use Valkyrja\Path\NativePathParser;
use Valkyrja\Routing\Annotation\Annotations\RouteAnnotations;
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
    public const APP                   = Valkyrja::class;
    public const ANNOTATIONS           = Annotations::class;
    public const ANNOTATIONS_PARSER    = AnnotationsParser::class;
    public const COMMAND_ANNOTATIONS   = CommandAnnotations::class;
    public const CONSOLE               = Console::class;
    public const CONSOLE_KERNEL        = NativeConsoleKernel::class;
    public const CONTAINER             = Container::class;
    public const CONTAINER_ANNOTATIONS = ContainerAnnotations::class;
    public const DISPATCHER            = Dispatcher::class;
    public const ENV                   = Env::class;
    public const EVENTS                = NativeEvents::class;
    public const FILESYSTEM            = FlyFilesystem::class;
    public const INPUT                 = Input::class;
    public const OUTPUT                = Output::class;
    public const OUTPUT_FORMATTER      = OutputFormatter::class;
    public const KERNEL                = NativeKernel::class;
    public const LISTENER_ANNOTATIONS  = ListenerAnnotations::class;
    public const PATH_GENERATOR        = NativePathGenerator::class;
    public const PATH_PARSER           = NativePathParser::class;
    public const REQUEST               = NativeRequest::class;
    public const RESPONSE              = NativeResponse::class;
    public const JSON_RESPONSE         = NativeJsonResponse::class;
    public const REDIRECT_RESPONSE     = NativeRedirectResponse::class;
    public const RESPONSE_BUILDER      = NativeResponseBuilder::class;
    public const ROUTER                = Router::class;
    public const ROUTE_ANNOTATIONS     = RouteAnnotations::class;
    public const SESSION               = NativeSession::class;
    public const VIEW                  = PhpView::class;
    public const CLIENT                = GuzzleClient::class;
    public const LOGGER                = LoggerServiceProvider::class;
    public const MAIL                  = PHPMailerMail::class;
    public const CRYPT                 = Crypt::class;
    public const CRYPT_ENCRYPTER       = SodiumEncrypter::class;
    public const CRYPT_DECRYPTER       = SodiumDecrypter::class;
    public const MODEL                 = NativeModel::class;
    public const ENTITY                = Entity::class;
    public const ENTITY_MANAGER        = PDOEntityManager::class;
    public const QUERY                 = PDOQuery::class;
    public const QUERY_BUILDER         = SqlQueryBuilder::class;
    public const REPOSITORY            = Repository::class;
    public const EXCEPTION_HANDLER     = NativeExceptionHandler::class;
    public const HTTP_EXCEPTION        = HttpException::class;
}
