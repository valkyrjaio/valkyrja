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

use Valkyrja\Annotation\NativeAnnotations;
use Valkyrja\Annotation\NativeAnnotationsParser;
use Valkyrja\Client\GuzzleClient;
use Valkyrja\Console\Annotations\NativeCommandAnnotations;
use Valkyrja\Console\Input\NativeInput;
use Valkyrja\Console\NativeConsole;
use Valkyrja\Console\NativeKernel as NativeConsoleKernel;
use Valkyrja\Console\Output\NativeOutput;
use Valkyrja\Console\Output\NativeOutputFormatter;
use Valkyrja\Container\Annotations\NativeContainerAnnotations;
use Valkyrja\Container\NativeContainer;
use Valkyrja\Crypt\SodiumCrypt;
use Valkyrja\Dispatcher\NativeDispatcher;
use Valkyrja\Enum\Enum;
use Valkyrja\Env\Env;
use Valkyrja\Event\Annotations\NativeListenerAnnotations;
use Valkyrja\Event\NativeEvents;
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
use Valkyrja\ORM\Repositories\NativeRepository;
use Valkyrja\Path\NativePathGenerator;
use Valkyrja\Path\NativePathParser;
use Valkyrja\Routing\Annotations\NativeRouteAnnotations;
use Valkyrja\Routing\NativeRouter;
use Valkyrja\Session\NativeSession;
use Valkyrja\Valkyrja;
use Valkyrja\View\PhpView;

/**
 * Enum Provider.
 *
 * @author Melech Mizrachi
 */
final class Provider extends Enum
{
    public const APP                   = Valkyrja::class;
    public const ANNOTATIONS           = NativeAnnotations::class;
    public const ANNOTATIONS_PARSER    = NativeAnnotationsParser::class;
    public const COMMAND_ANNOTATIONS   = NativeCommandAnnotations::class;
    public const CONSOLE               = NativeConsole::class;
    public const CONSOLE_KERNEL        = NativeConsoleKernel::class;
    public const CONTAINER             = NativeContainer::class;
    public const CONTAINER_ANNOTATIONS = NativeContainerAnnotations::class;
    public const DISPATCHER            = NativeDispatcher::class;
    public const ENV                   = Env::class;
    public const EVENTS                = NativeEvents::class;
    public const FILESYSTEM            = FlyFilesystem::class;
    public const INPUT                 = NativeInput::class;
    public const OUTPUT                = NativeOutput::class;
    public const OUTPUT_FORMATTER      = NativeOutputFormatter::class;
    public const KERNEL                = NativeKernel::class;
    public const LISTENER_ANNOTATIONS  = NativeListenerAnnotations::class;
    public const PATH_GENERATOR        = NativePathGenerator::class;
    public const PATH_PARSER           = NativePathParser::class;
    public const REQUEST               = NativeRequest::class;
    public const RESPONSE              = NativeResponse::class;
    public const JSON_RESPONSE         = NativeJsonResponse::class;
    public const REDIRECT_RESPONSE     = NativeRedirectResponse::class;
    public const RESPONSE_BUILDER      = NativeResponseBuilder::class;
    public const ROUTER                = NativeRouter::class;
    public const ROUTE_ANNOTATIONS     = NativeRouteAnnotations::class;
    public const SESSION               = NativeSession::class;
    public const VIEW                  = PhpView::class;
    public const CLIENT                = GuzzleClient::class;
    public const LOGGER                = LoggerServiceProvider::class;
    public const MAIL                  = PHPMailerMail::class;
    public const CRYPT                 = SodiumCrypt::class;
    public const MODEL                 = NativeModel::class;
    public const ENTITY                = Entity::class;
    public const ENTITY_MANAGER        = PDOEntityManager::class;
    public const QUERY                 = PDOQuery::class;
    public const QUERY_BUILDER         = SqlQueryBuilder::class;
    public const REPOSITORY            = NativeRepository::class;
    public const EXCEPTION_HANDLER     = NativeExceptionHandler::class;
    public const HTTP_EXCEPTION        = HttpException::class;
}
