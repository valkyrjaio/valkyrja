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

namespace Valkyrja;

use Valkyrja\Annotation\Annotator;
use Valkyrja\Api\Api;
use Valkyrja\Application\Application;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Auth\Auth;
use Valkyrja\Cache\Cache;
use Valkyrja\Client\Client;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Console\Output;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\HttpKernel\Kernel;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\Mail\Message as MailMessage;
use Valkyrja\Notification\NotifiableUser;
use Valkyrja\Notification\Notification;
use Valkyrja\Notification\Notifier;
use Valkyrja\Orm\Orm;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Routing\Collector;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;
use Valkyrja\Routing\Support\Abort;
use Valkyrja\Routing\Url;
use Valkyrja\Session\Session;
use Valkyrja\Sms\Message as SmsMessage;
use Valkyrja\Sms\Sms;
use Valkyrja\Support\Directory;
use Valkyrja\Validation\Validator;
use Valkyrja\View\Template;
use Valkyrja\View\View;

use function var_dump;

/**
 * Return the global $app variable.
 *
 * @return Application
 */
function app(): Application
{
    return Valkyrja::app();
}

/**
 * Abort the application due to error.
 *
 * @param int|null      $statusCode The status code to use
 * @param string|null   $message    [optional] The Exception message to throw
 * @param array|null    $headers    [optional] The headers to send
 * @param Response|null $response   [optional] The Response to send
 *
 * @throws HttpException
 *
 * @return never
 */
function abort(
    int|null $statusCode = null,
    string|null $message = null,
    array|null $headers = null,
    Response|null $response = null
): never {
    Abort::abort($statusCode, $message, $headers, $response);
}

/**
 * Abort the application due to error with a given response to send.
 *
 * @param Response $response The Response to send
 *
 * @throws HttpException
 *
 * @return never
 */
function abortResponse(Response $response): never
{
    Abort::response($response);
}

/**
 * Return the annotator instance from the container.
 *
 * @return Annotator
 */
function annotator(): Annotator
{
    return container()->getSingleton(Annotator::class);
}

/**
 * Return the api instance from the container.
 *
 * @return Api
 */
function api(): Api
{
    return container()->getSingleton(Api::class);
}

/**
 * Return the auth instance from the container.
 *
 * @return Auth
 */
function auth(): Auth
{
    return container()->getSingleton(Auth::class);
}

/**
 * Return the cache instance from the container.
 *
 * @return Cache
 */
function cache(): Cache
{
    return container()->getSingleton(Cache::class);
}

/**
 * Return the client instance from the container.
 *
 * @return Client
 */
function client(): Client
{
    return container()->getSingleton(Client::class);
}

/**
 * Get the config.
 *
 * @param string|null $key     [optional] The key to get
 * @param mixed       $default [optional] The default value if the key is not found
 *
 * @return mixed
 */
function config(string|null $key = null, mixed $default = null): mixed
{
    return Valkyrja::app()->config($key, $default);
}

/**
 * Get console.
 *
 * @return Console
 */
function console(): Console
{
    return container()->getSingleton(Console::class);
}

/**
 * Get container.
 *
 * @return Container
 */
function container(): Container
{
    return app()->container();
}

/**
 * Get dispatcher.
 *
 * @return Dispatcher
 */
function dispatcher(): Dispatcher
{
    return container()->getSingleton(Dispatcher::class);
}

/**
 * Get an environment variable.
 *
 * @param string|null $key     [optional] The variable to get
 * @param mixed       $default [optional] The default value to return
 *
 * @return mixed
 */
function env(string|null $key = null, mixed $default = null): mixed
{
    // Does not use the app() helper due to the self::$instance property
    // that Valkyrja::app() relies on has not been set yet when
    // this helper may be used.
    return Valkyrja::env($key, $default);
}

/**
 * Get events.
 *
 * @return Events
 */
function events(): Events
{
    return container()->getSingleton(Events::class);
}

/**
 * Get filesystem.
 *
 * @return Filesystem
 */
function filesystem(): Filesystem
{
    return container()->getSingleton(Filesystem::class);
}

/**
 * Get input.
 *
 * @return Input
 */
function input(): Input
{
    return container()->getSingleton(Input::class);
}

/**
 * Get kernel.
 *
 * @return Kernel
 */
function kernel(): Kernel
{
    return container()->getSingleton(Kernel::class);
}

/**
 * Get console kernel.
 *
 * @return ConsoleKernel
 */
function consoleKernel(): ConsoleKernel
{
    return container()->getSingleton(ConsoleKernel::class);
}

/**
 * Get the crypt.
 *
 * @return Crypt
 */
function crypt(): Crypt
{
    return container()->getSingleton(Crypt::class);
}

/**
 * Get logger.
 *
 * @return Logger
 */
function logger(): Logger
{
    return container()->getSingleton(Logger::class);
}

/**
 * Get mail.
 *
 * @return Mail
 */
function mail(): Mail
{
    return container()->getSingleton(Mail::class);
}

/**
 * Get a new mail message.
 *
 * @param string|null $name [optional] The name
 *
 * @return MailMessage
 */
function mailMessage(string|null $name = null): MailMessage
{
    return \Valkyrja\mail()->createMessage($name);
}

/**
 * Get notification manager.
 *
 * @return Notifier
 */
function notifier(): Notifier
{
    return container()->getSingleton(Notifier::class);
}

/**
 * Notify a user.
 *
 * @param Notification   $notification The notification
 * @param NotifiableUser $user         The user
 *
 * @return void
 */
function notifyUser(Notification $notification, NotifiableUser $user): void
{
    \Valkyrja\notifier()->notifyUser($notification, $user);
}

/**
 * Notify users.
 *
 * @param Notification     $notification The notification
 * @param NotifiableUser[] $users        The users
 *
 * @return void
 */
function notifyUsers(Notification $notification, NotifiableUser ...$users): void
{
    \Valkyrja\notifier()->notifyUsers($notification, ...$users);
}

/**
 * Get path generator.
 *
 * @return PathGenerator
 */
function pathGenerator(): PathGenerator
{
    return container()->getSingleton(PathGenerator::class);
}

/**
 * Get path parser.
 *
 * @return PathParser
 */
function pathParser(): PathParser
{
    return container()->getSingleton(PathParser::class);
}

/**
 * Get the ORM manager.
 *
 * @return Orm
 */
function orm(): Orm
{
    return container()->getSingleton(Orm::class);
}

/**
 * Get output.
 *
 * @return Output
 */
function output(): Output
{
    return container()->getSingleton(Output::class);
}

/**
 * Get reflector.
 *
 * @return Reflector
 */
function reflector(): Reflector
{
    return container()->getSingleton(Reflector::class);
}

/**
 * Get request.
 *
 * @return Request
 */
function request(): Request
{
    return container()->getSingleton(Request::class);
}

/**
 * Get router.
 *
 * @return Router
 */
function router(): Router
{
    return container()->getSingleton(Router::class);
}

/**
 * Get route collector.
 *
 * @return Collector
 */
function collector(): Collector
{
    return container()->getSingleton(Collector::class);
}

/**
 * Get routing url service.
 *
 * @return Url
 */
function url(): Url
{
    return container()->getSingleton(Url::class);
}

/**
 * Get a route by name.
 *
 * @param string $name The name of the route to get
 *
 * @throws InvalidRouteName
 *
 * @return Route
 */
function route(string $name): Route
{
    return router()->getRoute($name);
}

/**
 * Get a route url by name.
 *
 * @param string     $name     The name of the route to get
 * @param array|null $data     [optional] The route data if dynamic
 * @param bool       $absolute [optional] Whether this url should be absolute
 *
 * @return string
 */
function routeUrl(string $name, array|null $data = null, bool|null $absolute = null): string
{
    return url()->getUrl($name, $data, $absolute);
}

/**
 * Get the response builder.
 *
 * @return ResponseFactory
 */
function responseFactory(): ResponseFactory
{
    return container()->getSingleton(ResponseFactory::class);
}

/**
 * Return a new response from the application.
 *
 * @param string|null $content    [optional] The content to set
 * @param int|null    $statusCode [optional] The status code to set
 * @param array|null  $headers    [optional] The headers to set
 *
 * @return Response
 */
function response(string|null $content = null, int|null $statusCode = null, array|null $headers = null): Response
{
    return responseFactory()->createResponse($content, $statusCode, $headers);
}

/**
 * Return a new json response from the application.
 *
 * @param array|null $data       [optional] An array of data
 * @param int|null   $statusCode [optional] The status code to set
 * @param array|null $headers    [optional] The headers to set
 *
 * @return JsonResponse
 */
function json(array|null $data = null, int|null $statusCode = null, array|null $headers = null): JsonResponse
{
    return responseFactory()->createJsonResponse($data, $statusCode, $headers);
}

/**
 * Return a new redirect response from the application.
 *
 * @param string|null $uri        [optional] The URI to redirect to
 * @param int|null    $statusCode [optional] The response status code
 * @param array|null  $headers    [optional] An array of response headers
 *
 * @return RedirectResponse
 */
function redirect(string|null $uri = null, int|null $statusCode = null, array|null $headers = null): RedirectResponse
{
    return responseFactory()->createRedirectResponse($uri, $statusCode, $headers);
}

/**
 * Return a new redirect response from the application for a given route.
 *
 * @param string     $route      The route to match
 * @param array|null $parameters [optional] Any parameters to set for dynamic routes
 * @param int|null   $statusCode [optional] The response status code
 * @param array|null $headers    [optional] An array of response headers
 *
 * @return RedirectResponse
 */
function redirectRoute(
    string $route,
    array|null $parameters = null,
    int|null $statusCode = null,
    array|null $headers = null
): RedirectResponse {
    return responseFactory()->route($route, $parameters, $statusCode, $headers);
}

/**
 * Redirect to a given uri, and abort the application.
 *
 * @param string|null $uri        [optional] The URI to redirect to
 * @param int|null    $statusCode [optional] The response status code
 * @param array|null  $headers    [optional] An array of response headers
 *
 * @throws HttpRedirectException
 *
 * @return never
 */
function redirectTo(
    string|null $uri = null,
    int|null $statusCode = null,
    array|null $headers = null
): never {
    Abort::redirect($uri, $statusCode, $headers);
}

/**
 * Return the session.
 *
 * @return Session
 */
function session(): Session
{
    return container()->getSingleton(Session::class);
}

/**
 * Get SMS.
 *
 * @return Sms
 */
function sms(): Sms
{
    return container()->getSingleton(Sms::class);
}

/**
 * Get a new SMS message.
 *
 * @param string|null $name [optional] The name
 *
 * @return SmsMessage
 */
function smsMessage(string|null $name = null): SmsMessage
{
    return \Valkyrja\sms()->createMessage($name);
}

/**
 * Get validator.
 *
 * @return Validator
 */
function validator(): Validator
{
    return container()->getSingleton(Validator::class);
}

/**
 * Helper function to get a new view.
 *
 * @return View
 */
function view(): View
{
    return container()->getSingleton(View::class);
}

/**
 * Helper function to get a new template.
 *
 * @param string $template  [optional] The template to use
 * @param array  $variables [optional] The variables to use
 *
 * @return Template
 */
function template(string $template, array $variables = []): Template
{
    return view()->createTemplate($template, $variables);
}

/**
 * Dump the passed variables and die.
 *
 * @param mixed ...$args The arguments to dump
 *
 * @return never
 */
function dd(...$args): never
{
    var_dump($args);

    exit(1);
}

/**
 * Helper function to get base path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function basePath(string|null $path = null): string
{
    return Directory::basePath($path);
}

/**
 * Helper function to get app path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function appPath(string|null $path = null): string
{
    return Directory::appPath($path);
}

/**
 * Helper function to get bootstrap path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function bootstrapPath(string|null $path = null): string
{
    return Directory::bootstrapPath($path);
}

/**
 * Helper function to get env path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function envPath(string|null $path = null): string
{
    return Directory::envPath($path);
}

/**
 * Helper function to get config path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function configPath(string|null $path = null): string
{
    return Directory::configPath($path);
}

/**
 * Helper function to get commands path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function commandsPath(string|null $path = null): string
{
    return Directory::commandsPath($path);
}

/**
 * Helper function to get events path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function eventsPath(string|null $path = null): string
{
    return Directory::eventsPath($path);
}

/**
 * Helper function to get routes path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function routesPath(string|null $path = null): string
{
    return Directory::routesPath($path);
}

/**
 * Helper function to get services path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function servicesPath(string|null $path = null): string
{
    return Directory::servicesPath($path);
}

/**
 * Helper function to get public path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function publicPath(string|null $path = null): string
{
    return Directory::publicPath($path);
}

/**
 * Helper function to get resources path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function resourcesPath(string|null $path = null): string
{
    return Directory::resourcesPath($path);
}

/**
 * Helper function to get storage path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function storagePath(string|null $path = null): string
{
    return Directory::storagePath($path);
}

/**
 * Helper function to get framework storage path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function frameworkStoragePath(string|null $path = null): string
{
    return Directory::frameworkStoragePath($path);
}

/**
 * Helper function to get cache path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function cachePath(string|null $path = null): string
{
    return Directory::cachePath($path);
}

/**
 * Helper function to get tests path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function testsPath(string|null $path = null): string
{
    return Directory::testsPath($path);
}

/**
 * Helper function to get vendor path.
 *
 * @param string|null $path [optional] The path to append
 *
 * @return string
 */
function vendorPath(string|null $path = null): string
{
    return Directory::vendorPath($path);
}
