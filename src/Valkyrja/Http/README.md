# HTTP

## Introduction

Valkyrja's HTTP layer is built around PSR-7 messages, PSR-15 middleware, and a
structured pipeline that gives you predictable, observable control over every
phase of request handling. Whether a route matches, fails to match, throws an
exception, or completes normally, there is a dedicated middleware stage for it.

The two primary concerns in this component are **routing** — matching an
incoming request to a handler — and **middleware** — operating on the request
and response before, during, and after dispatch. Both are handled through the
same Dispatcher that underpins events and CLI commands, so the behavioral
contracts are consistent across the framework.

## Configuration

HTTP applications are bootstrapped using the `HttpConfig` typed configuration
class. Rather than reading from environment variables at runtime, all
configuration is expressed as PHP constructor arguments with sensible defaults.

```php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;

Http::run(new HttpConfig(
    namespace:     'App',
    dir:           __DIR__,
    environment:   'production',
    debugMode:     false,
    timezone:      'UTC',
    key:           'your-application-key',
    dataPath:      'App/Provider/Data',
    dataNamespace: 'App\\Provider\\Data',
));
```

The `dataPath` and `dataNamespace` properties tell the framework where to write
and load generated route data files — the compiled PHP classes that make
production routing allocation-free.

## Entry Point

`Http::run()` is the single entry point for an HTTP application. It boots the
application, resolves the `RequestHandlerContract` from the container, creates a
`ServerRequest` from PHP's superglobals via `RequestFactory::fromGlobals()`, and
hands it to the handler:

```php
// public/index.php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;

Http::run(new HttpConfig(
    dir: dirname(__DIR__),
));
```

Everything that happens after that point — middleware, routing, dispatch,
response sending — is managed by `RequestHandler`.

## Routing

### Route Providers

Routes are registered through **route providers** — classes that implement
`ProviderContract` and return a list of controller classes and/or pre-built
route objects. The framework iterates over all registered providers during
bootstrap to build the route collection.

```php
use Valkyrja\Http\Routing\Provider\Abstract\Provider;

class ApiRouteProvider extends Provider
{
    public static function getControllerClasses(): array
    {
        return [
            UserController::class,
            PostController::class,
        ];
    }
}
```

When `getControllerClasses()` returns classes, the framework's
`AttributeCollector` reflects on each class, extracts `#[Route]` attributes from
its methods, and adds the resulting routes to the collection. When `getRoutes()`
returns `Route` objects directly, those are passed through the `Processor` and
added as well. You can use either mechanism or both.

Route providers are wired into the application through a component provider's
`getHttpProviders()` method. The component provider itself is listed in
`HttpConfig`'s `providers` array, which is the same mechanism used for container
service providers and event providers.

### Attribute-Based Registration

The idiomatic way to define routes in Valkyrja is to annotate controller methods
with the `#[Route]` attribute:

```php
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route;

class UserController
{
    #[Route(path: '/users', name: 'users.index')]
    public function index(): ResponseContract
    {
        // GET /users
    }

    #[Route(path: '/users', name: 'users.store', requestMethods: [RequestMethod::POST])]
    public function store(): ResponseContract
    {
        // POST /users
    }

    #[Route(path: '/users/{id}', name: 'users.show')]
    public function show(int $id): ResponseContract
    {
        // GET /users/{id}
    }
}
```

The `#[Route]` attribute is repeatable — a single method can handle multiple
paths or methods by stacking attributes. The default `requestMethods` is
`[RequestMethod::HEAD, RequestMethod::GET]`.

### Route Modifiers

Several companion attributes refine how individual routes behave:

**`#[Route\Path]`** — Overrides or prefixes the route path at the class or
method level.

**`#[Route\Name]`** — Overrides the route name at the class or method level.

**`#[Route\RequestMethod]`** — Sets allowed HTTP methods on a method, separate
from the `#[Route]` declaration:

```php
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Http\Message\Enum\RequestMethod as Method;

#[Route(path: '/posts/{id}', name: 'posts.update')]
#[RequestMethod(Method::PUT, Method::PATCH)]
public function update(int $id): ResponseContract { ... }
```

**`#[Route\Middleware]`** — Attaches a middleware class to a route. The
middleware type determines which pipeline stage it runs in:

```php
use Valkyrja\Http\Routing\Attribute\Route\Middleware;

#[Route(path: '/admin', name: 'admin.dashboard')]
#[Middleware(AuthMiddleware::class)]
public function dashboard(): ResponseContract { ... }
```

### Dynamic Routes and Parameters

Routes with `{param}` segments are automatically treated as dynamic routes. The
`#[Route\Parameter]` attribute allows you to declare regex constraints and cast
rules per parameter:

```php
use Valkyrja\Http\Routing\Attribute\Route\Parameter;

#[Route(path: '/articles/{slug}', name: 'articles.show')]
#[Parameter(name: 'slug', regex: '[a-z0-9-]+')]
public function show(string $slug): ResponseContract { ... }
```

### HTTP Methods

All standard HTTP methods are available via the `RequestMethod` enum:

| Case      | Value     |
|-----------|-----------|
| `GET`     | `GET`     |
| `HEAD`    | `HEAD`    |
| `POST`    | `POST`    |
| `PUT`     | `PUT`     |
| `DELETE`  | `DELETE`  |
| `PATCH`   | `PATCH`   |
| `OPTIONS` | `OPTIONS` |
| `CONNECT` | `CONNECT` |
| `TRACE`   | `TRACE`   |
| `ANY`     | `ANY`     |

### Route Collection and Data Generation

During development (`debugMode: true`), the framework collects routes fresh on
every request by reflecting on all registered controller classes. In production,
the route collection is compiled into a generated PHP data class — a static file
that the container loads directly, requiring no reflection at runtime.

You generate this file using the built-in CLI command:

```bash
php cli http:data:generate
```

The generated class is written to the path defined by `dataPath` and
`dataNamespace` in your configuration and is loaded automatically when
`debugMode` is `false`.

### URL Generation

The `UrlContract` service generates URLs from route names. Inject it wherever
needed:

```php
use Valkyrja\Http\Routing\Url\Contract\UrlContract;

public function __construct(private UrlContract $url) {}

public function someAction(): ResponseContract
{
    $url = $this->url->getUrl('users.show', ['id' => 42]);
    // /users/42
}
```

## Request and Response

### ServerRequest

The `ServerRequest` class is a PSR-7 `ServerRequestInterface` implementation. It
is created from PHP's superglobals at the entry point and is immutable — all
`with*` methods return a new instance.

```php
use Valkyrja\Http\Message\Request\ServerRequest;

$method   = $request->getMethod();          // RequestMethod enum
$uri      = $request->getUri();             // UriInterface
$query    = $request->getQueryParams();
$body     = $request->getParsedBody();
$cookies  = $request->getCookieParams();
$files    = $request->getUploadedFiles();
$attrs    = $request->getAttributes();

$isAjax   = $request->isXmlHttpRequest();
```

### Responses

Valkyrja provides several named response types, all implementing
`ResponseContract`:

```php
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\HtmlResponse;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Response\EmptyResponse;
use Valkyrja\Http\Message\Enum\StatusCode;

// Plain response with a body
$response = new Response(body: $stream, statusCode: StatusCode::OK);

// Typed convenience responses
$json     = new JsonResponse(['user' => $user]);
$html     = new HtmlResponse('<h1>Hello</h1>');
$text     = new TextResponse('Hello');
$redirect = new RedirectResponse('/dashboard');
$empty    = new EmptyResponse(StatusCode::NO_CONTENT);
```

The `ResponseFactory` available via injection provides a fluent interface for
building redirect and other common responses, including one that resolves URLs
by route name.

### Request and Response Structs

For routes that need input validation or structured output, Valkyrja provides
the struct system. A class implementing `RequestStructContract` carries the
route's validation rules and knows how to extract typed data from the request. A
class implementing `ResponseStructContract` shapes what goes into the response.

Both are attached to a route via companion attributes:

```php
#[Route(path: '/users', name: 'users.store', requestMethods: [RequestMethod::POST])]
#[Route\RequestStruct(CreateUserRequest::class)]
#[Route\ResponseStruct(UserResponse::class)]
public function store(): ResponseContract { ... }
```

Or inline in the `#[Route]` declaration:

```php
#[Route(
    path: '/users',
    name: 'users.store',
    requestMethods: [RequestMethod::POST],
    requestStruct:  new CreateUserRequest(),
    responseStruct: new UserResponse(),
)]
public function store(): ResponseContract { ... }
```

## The Middleware Pipeline

Every HTTP request passes through a structured seven-stage middleware pipeline.
Each stage has a dedicated contract, and middleware classes implement whichever
contracts correspond to the stages they participate in. A single class can
implement multiple contracts.

### Stage 1 — RequestReceived

`RequestReceivedMiddlewareContract` fires the moment a request enters the
handler, before any route matching occurs. It receives the raw `ServerRequest`
and can either return a modified request (to continue) or return a
`ResponseContract` directly (short-circuiting the pipeline):

```php
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;

class MaintenanceModeMiddleware implements RequestReceivedMiddlewareContract
{
    public function requestReceived(
        ServerRequestContract $request,
        RequestReceivedHandlerContract $handler
    ): ServerRequestContract|ResponseContract {
        if ($this->isUnderMaintenance()) {
            return new HtmlResponse('Service unavailable.', StatusCode::SERVICE_UNAVAILABLE);
        }

        return $handler->requestReceived($request);
    }
}
```

`RequestReceived` middleware is global — it runs on every request regardless of
which route is matched. Configure it in `RequestHandler`.

The response cache (`CacheResponseMiddleware`) operates at this stage: on the
way in, it checks whether a cached response file exists for the request path and
method; if so, it returns it immediately without executing any further pipeline
stages.

### Stage 2 — RouteMatched

`RouteMatchedMiddlewareContract` fires after a route has been matched but before
its handler is dispatched. It receives both the request and the matched
`RouteContract`, and can return a modified route or short-circuit with a
response:

```php
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;

class AuthMiddleware implements RouteMatchedMiddlewareContract
{
    public function routeMatched(
        ServerRequestContract $request,
        RouteContract $route,
        RouteMatchedHandlerContract $handler
    ): RouteContract|ResponseContract {
        if (! $this->isAuthenticated($request)) {
            return new RedirectResponse('/login');
        }

        return $handler->routeMatched($request, $route);
    }
}
```

`RouteMatched` middleware can be declared globally or per-route via the
`routeMatchedMiddleware` parameter of `#[Route]`.

### Stage 3 — RouteNotMatched

`RouteNotMatchedMiddlewareContract` fires when the router cannot match the
incoming request to any registered route. It receives the request and a default
404 response. This is the right place to implement custom 404 pages or fallback
logic:

```php
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

class NotFoundMiddleware implements RouteNotMatchedMiddlewareContract
{
    public function routeNotMatched(
        ServerRequestContract $request,
        ResponseContract $response,
        RouteNotMatchedHandlerContract $handler
    ): ResponseContract {
        return new HtmlResponse(
            $this->renderNotFoundPage($request),
            StatusCode::NOT_FOUND
        );
    }
}
```

`RouteNotMatched` middleware is global — it applies to all unmatched requests.

### Stage 4 — RouteDispatched

`RouteDispatchedMiddlewareContract` fires after the route's handler has been
called and a response has been produced. It receives the request, the response,
and the matched route. This is the right place for post-dispatch concerns:
response transformation, logging, adding headers:

```php
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;

class JsonApiMiddleware implements RouteDispatchedMiddlewareContract
{
    public function routeDispatched(
        ServerRequestContract $request,
        ResponseContract $response,
        RouteContract $route,
        RouteDispatchedHandlerContract $handler
    ): ResponseContract {
        return $handler->routeDispatched($request, $response->withHeader('Content-Type', 'application/json'), $route);
    }
}
```

Declared per-route via `routeDispatchedMiddleware`.

### Stage 5 — ThrowableCaught

`ThrowableCaughtMiddlewareContract` fires when any `Throwable` is caught during
request handling. It receives the request, a default error response, and the
throwable itself:

```php
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;

class ErrorReportingMiddleware implements ThrowableCaughtMiddlewareContract
{
    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $throwable,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        $this->logger->error($throwable->getMessage(), ['exception' => $throwable]);

        return $handler->throwableCaught($request, $response, $throwable);
    }
}
```

Declared per-route via `throwableCaughtMiddleware`, or globally in
`RequestHandler`.

### Stage 6 — SendingResponse

`SendingResponseMiddlewareContract` fires after the response is finalized but
before it is written to the output buffer. This is the right place to add
universal headers, compress bodies, or strip sensitive data:

```php
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;

class CorsPolicyMiddleware implements SendingResponseMiddlewareContract
{
    public function sendingResponse(
        ServerRequestContract $request,
        ResponseContract $response,
        SendingResponseHandlerContract $handler
    ): ResponseContract {
        return $handler->sendingResponse(
            $request,
            $response->withHeader('Access-Control-Allow-Origin', '*')
        );
    }
}
```

Declared per-route via `sendingResponseMiddleware`.

The built-in `NoCacheResponseMiddleware` operates at this stage — it adds
`Cache-Control: no-cache, no-store`, `Pragma: no-cache`, and a past `Expires`
header to prevent client-side caching of sensitive responses.

### Stage 7 — Terminated

`TerminatedMiddlewareContract` fires after the response has been sent to the
client. At this point the user has already received the response; work done here
does not affect what they see. It is the appropriate stage for deferred side
effects: writing logs, dispatching queued events, clearing caches:

```php
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;

class ActivityLogMiddleware implements TerminatedMiddlewareContract
{
    public function terminated(
        ServerRequestContract $request,
        ResponseContract $response,
        TerminatedHandlerContract $handler
    ): void {
        $this->activityLog->record($request, $response);

        $handler->terminated($request, $response);
    }
}
```

Declared per-route via `terminatedMiddleware`.

The `CacheResponseMiddleware` also hooks into this stage: if the response was a
success (not a 5xx) and has not yet been cached, it serializes the response to a
PHP file on disk, making it available for instantaneous replay on future
requests.

### Pipeline Summary

| Stage             | When it fires                     | Can short-circuit | Scope     |
|-------------------|-----------------------------------|-------------------|-----------|
| `RequestReceived` | Before route matching             | Yes               | Global    |
| `RouteMatched`    | After match, before dispatch      | Yes               | Per-route |
| `RouteNotMatched` | When no route matches             | No                | Global    |
| `RouteDispatched` | After dispatch, before sending    | No                | Per-route |
| `ThrowableCaught` | When a throwable is caught        | No                | Per-route |
| `SendingResponse` | Before writing response to output | No                | Per-route |
| `Terminated`      | After response is sent            | No                | Per-route |

## Response Caching

Valkyrja ships a full-response cache middleware — `CacheResponseMiddleware` —
that stores serialized response objects on the filesystem and replays them on
subsequent identical requests. Because it operates at the `RequestReceived`
stage, a cache hit bypasses route matching, dispatch, and all other middleware
entirely.

Enable it by registering it as global `RequestReceived` middleware and pointing
it at a writable directory:

```php
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;

// In your RequestHandler configuration or middleware provider:
new CacheResponseMiddleware(
    filePath: '/var/cache/responses',
    debug: false,
);
```

The cache key is an MD5 hash of the request path combined with the HTTP method.
Cached entries expire after 30 minutes (1800 seconds); expired files are deleted
and the request proceeds normally. Responses with a 5xx status code are never
cached. Enabling `debug: true` disables cache reads (writes still occur, so
caches warm even in dev, but stale caches are never served).

To prevent caching on routes that return user-specific or sensitive data, add
`NoCacheResponseMiddleware` to those routes' `sendingResponseMiddleware`:

```php
use Valkyrja\Http\Server\Middleware\SendingResponse\NoCacheResponseMiddleware;

#[Route(
    path: '/account',
    name: 'account.show',
    sendingResponseMiddleware: [NoCacheResponseMiddleware::class],
)]
public function show(): ResponseContract { ... }
```

## HttpException

When application code needs to produce a specific HTTP error response, throw an
`HttpException`. The `RequestHandler` detects it and uses its embedded
`StatusCode` and optional response body, rather than falling back to a generic
500:

```php
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Enum\StatusCode;

throw new HttpException(StatusCode::NOT_FOUND, 'Resource not found.');
```

`ThrowableCaught` middleware sees the exception before the handler's default
behaviour takes effect, so you can override the response further at that stage.

## Full Request Lifecycle

From `Http::run()` to process exit, the lifecycle is:

1. `HttpConfig` is validated and the application is bootstrapped.
2. Component providers register services into the container.
3. Route providers register routes into the collection (or load the compiled
   data file).
4. `RequestFactory::fromGlobals()` builds a `ServerRequest` from `$_SERVER`,
   `$_GET`, `$_POST`, `$_COOKIE`, and `$_FILES`.
5. `RequestHandler::run()` is called.
6. `RequestReceived` middleware runs (cache check, maintenance mode, etc.).
7. The `Router` asks the `Matcher` to find a matching route.
8. **If no route matches**: `RouteNotMatched` middleware runs and produces a 404
   response.
9. **If a route matches**: `RouteMatched` middleware runs (authentication,
   authorization).
10. The `Dispatcher` calls the matched controller method, injecting dependencies
    from the container.
11. `RouteDispatched` middleware runs (response transformation, logging).
12. **If a throwable is caught** at any point: `ThrowableCaught` middleware
    runs.
13. `SendingResponse` middleware runs (final header injection, compression).
14. The response is written to the output buffer; the session is closed; FastCGI
    or Litespeed finish-request is called if available.
15. `Terminated` middleware runs (deferred work, cache writes, analytics).
