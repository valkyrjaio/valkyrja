# HTTP

The HTTP component matches an incoming request to a handler and runs middleware
around every phase of the dispatch. The request and response classes follow the
shape of PSR-7, but they do not implement the `Psr\Http\Message\*` interfaces.
The middleware pipeline is Valkyrja's own; the component does not use PSR-15
internally. The [PSR compatibility](#psr-compatibility) section lists the
bridges to code that expects the PSR interfaces.

## Configuration and entry point

The `HttpConfig` class holds all configuration as constructor arguments, and
every argument has a default. `Http::run()` is the entry point:

```php
// public/index.php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\Http;

Http::run(new HttpConfig(
    namespace:   'App',
    dir:         __DIR__,
    environment: 'production',
    debugMode:   false,
    timezone:    'UTC',
    key:         'your-application-key',
));
```

Each named argument overrides one constructor default; pass only the values
that differ. Convention: hold your application's real values in the config
object — one config file per environment, or values that your own bootstrap
reads from an env file. The constructor defaults are generic placeholders. The
`providers` array lists the component providers, and seven middleware arrays
configure the global pipeline — see
[the middleware pipeline](#the-middleware-pipeline).

`Http::run()` boots the application, builds a `ServerRequest` from the
superglobals with `RequestFactory::fromGlobals()`, resolves the
`RequestHandlerContract` from the container, and runs the request through it.

## Routing

### Route providers

A route provider implements `HttpRouteProviderContract`. It returns a list of
controller classes to reflect on, a list of pre-built route objects, or both:

```php
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;

class UserRouteProvider implements HttpRouteProviderContract
{
    public function getControllerClasses(): array
    {
        return [UserController::class];
    }

    public function getRoutes(): array
    {
        return [];
    }
}
```

The `AttributeRouteCollector` reflects on each controller class and collects the
routes that its `#[Route]` attributes declare. The `Processor` prepares each
pre-built route from `getRoutes()`. A component provider returns the route
providers from its `getHttpProviders()` method and is listed in the `providers`
array of `HttpConfig`.

### Attribute routes

Declare a route with the `#[Route]` attribute on a controller method. The
attribute is repeatable, and the default request methods are `HEAD` and `GET`:

```php
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Routing\Attribute\Route;

class UserController
{
    #[Route(path: '/users', name: 'users.index')]
    public function index(): ResponseContract
    {
        return new JsonResponse(['users' => []]);
    }

    #[Route(path: '/users', name: 'users.store', requestMethods: [RequestMethod::POST])]
    public function store(): ResponseContract
    {
        return new JsonResponse([], StatusCode::CREATED);
    }
}
```

Every attribute on the method applies to all routes that the method declares.
Give a route its own configuration through that route's own arguments.

### Route handlers

Every route has a handler with the signature
`callable(ContainerContract $container, RouteContract $route): ResponseContract`.
The handler resolves the controller from the container and calls it. A route
without a handler returns an empty `Response`. Wire a handler with
`#[RouteHandler([UserRouteProvider::class, 'showHandler'])]` on the routed
method, or pass the `handler` argument of `#[Route]` directly.

The matched path values live on the route's `Parameter` objects. The handler
reads a value from the route:

```php
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

public static function showHandler(ContainerContract $container, RouteContract $route): ResponseContract
{
    /** @var DynamicRouteContract $route */
    $id = (int) $route->getParameter('id')->getValue();

    return $container->getSingleton(UserController::class)->show($id);
}
```

A generated data class can export an array-callable handler; it cannot export a
closure. Prefer array callables for routes that the data cache stores.

### Dynamic routes and parameters

A path with a `{param}` segment becomes a dynamic route. The
`Valkyrja\Http\Routing\Attribute\Parameter` attribute declares the regex and an
optional cast for each parameter. Place it on the method or on the method's
parameter:

```php
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Constant\Regex;

#[Route(path: '/articles/{slug}', name: 'articles.show')]
#[Parameter(name: 'slug', regex: Regex::SLUG)]
public function show(string $slug): ResponseContract
{
    return new JsonResponse(['slug' => $slug]);
}
```

The `#[DynamicRoute]` attribute declares the parameters inline instead. Its
`parameters` argument is required and takes `Parameter` data objects:

```php
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Attribute\DynamicRoute;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;

#[DynamicRoute(
    path: '/users/{id}',
    name: 'users.delete',
    parameters: [new Parameter(name: 'id', regex: Regex::ID)],
    requestMethods: [RequestMethod::DELETE],
)]
public function delete(int $id): ResponseContract
{
    return new JsonResponse(['id' => $id]);
}
```

The `Regex` constant class ships patterns for common shapes: `ID`, `NUM`,
`SLUG`, `ALPHA`, `UUID`, `ULID`, and more. The `RequestMethod` enum has one case
per HTTP method, each backed by its method name, plus `ANY`.

### Route modifiers

- **`#[Route\Path]`** — on a class, prepends a path to every route in the class;
  on a method, appends a path to that method's routes.
- **`#[Route\Name]`** — on a class, prefixes every route name in the class; on a
  method, suffixes that method's route names.
- **`#[Route\RequestMethod]`** — adds request methods to a route, apart from the
  `#[Route]` declaration. Shorthand subclasses in `Attribute\Route\RequestMethod`
  add one method each; `#[Patch]` adds `PATCH` to the default `HEAD` and `GET`.
- **`#[Route\Middleware]`** — attaches a middleware class to a route. The
  collector reads the contracts the class implements and assigns it to the
  matching pipeline stages.

### The route collection and debug mode

`HttpRoutingServiceProvider::publishRouteCollection()` gates the collection on
the debug mode. When `debugMode` is `true`, the framework collects the routes
fresh on every request from the route providers. When `debugMode` is `false`,
the collection loads from the `HttpRoutingData` singleton. A generated data
class under `dataPath` provides that singleton in production; without one, the
default publisher builds the data from the route providers at boot.

The `http:list` CLI command prints every registered route.

### URL generation

The `UrlContract` service builds a URL from a route name. The `data` argument is
required; pass an empty array for a static route:

```php
use Valkyrja\Http\Routing\Url\Contract\UrlContract;

// $url is an injected UrlContract
$path = $url->getUrl('users.show', ['id' => 42]); // /users/42
```

## Requests and responses

### ServerRequest

`RequestFactory::fromGlobals()` builds the `ServerRequest` at the entry point.
The object is immutable — every `with*` method returns a new instance. The
getters return typed param collections, not arrays: `getQueryParams()` returns a
`QueryParamCollectionContract`, `getParsedBody()` a
`ParsedBodyParamCollectionContract`, and so on. Each collection exposes `get()`
for a single value.

### Responses

Every response type implements `ResponseContract`. `Response` takes a stream
body and a status code. `JsonResponse` takes a data array. `HtmlResponse`,
`TextResponse`, and `XmlResponse` take a string body, and `EmptyResponse` is a
204 with a read-only body. `RedirectResponse` takes a `UriContract`, not a
string:

```php
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Uri\Uri;

$json     = new JsonResponse(['user' => 'melech']);
$redirect = new RedirectResponse(new Uri(path: '/dashboard'));
$redirect = RedirectResponse::createFromUri(new Uri(path: '/dashboard'));

// $factory is an injected RoutingResponseFactoryContract
$redirect = $factory->createRouteRedirectResponse('users.show', ['id' => 42]);
```

`ResponseFactoryContract::createRedirectResponse()` accepts a URI string. The
route-name redirect above lives on `RoutingResponseFactoryContract`, in
`Http\Routing\Factory`.

### Structs

A struct is an enum. A request struct implements `RequestStructContract` and
declares one case per expected field plus the validation rules. The traits in
`Struct\Request\Trait` supply the data extraction for the query, the parsed
body, or a JSON body:

```php
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Request\Trait\ParsedBodyRequestStruct;
use Valkyrja\Validation\Rule\Is\Required;

enum CreateUserRequestStruct implements RequestStructContract
{
    use ParsedBodyRequestStruct;

    case username;

    public static function getValidationRules(ServerRequestContract $request): array
    {
        $username = $request->getParsedBody()->get(self::username->name);

        return [
            self::username->name => [new Required($username, 'The username is required')],
        ];
    }
}
```

Attach a struct to a route with an enum case — an instance of the contract,
never a `::class` string. Any case of the enum works, because the middleware
calls only static methods:

```php
#[Route(path: '/users', name: 'users.store', requestMethods: [RequestMethod::POST])]
#[Route\RequestStruct(CreateUserRequestStruct::username)]
public function store(): ResponseContract
{
    return new JsonResponse([], StatusCode::CREATED);
}
```

The `requestStruct` and `responseStruct` arguments of `#[Route]` accept the same
instances inline. Two middleware classes act on the structs:
`RequestStructMiddleware` (a `RouteMatched` middleware) rejects a request that
fails validation with a 400 response, and `ResponseStructMiddleware` (a
`RouteDispatched` middleware) shapes the outgoing response. Register them
globally or per route for structs to take effect.

## PSR compatibility

The pipeline works exclusively with Valkyrja's own contracts. Wrapper classes in
`Psr/` subdirectories adapt the objects for third-party code that depends on
`psr/http-message` or `psr/http-server-handler`. Each wrapper holds a Valkyrja
object and delegates every call to it.

| Wrapper class                            | Implements               | Wraps                   |
| :--------------------------------------- | :----------------------- | :---------------------- |
| `Http\Message\Stream\Psr\Stream`         | `StreamInterface`        | `StreamContract`        |
| `Http\Message\Uri\Psr\Uri`               | `UriInterface`           | `UriContract`           |
| `Http\Message\Request\Psr\Request`       | `RequestInterface`       | `RequestContract`       |
| `Http\Message\Request\Psr\ServerRequest` | `ServerRequestInterface` | `ServerRequestContract` |
| `Http\Message\Response\Psr\Response`     | `ResponseInterface`      | `ResponseContract`      |
| `Http\Message\File\Psr\UploadedFile`     | `UploadedFileInterface`  | `UploadedFileContract`  |

Static factories convert in the other direction: `PsrStreamFactory`,
`PsrUriFactory`, and `PsrRequestFactory` each have a `fromPsr()` method, and
`PsrHeaderFactory` and `PsrUploadedFileFactory` convert both ways.

`Http\Server\Psr\RequestHandler` bridges PSR-15. Its `handle()` method converts
the incoming PSR-7 request, runs it through the Valkyrja request handler, and
returns the response in a PSR-7 wrapper.

## The middleware pipeline

The pipeline has seven named stages. Each stage has its own contract, and one
class can implement several contracts. A middleware receives the stage handler
as its last argument; it calls the handler to continue, or it returns a response
to stop the pipeline early:

```php
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

class AuthMiddleware implements RouteMatchedMiddlewareContract
{
    public function routeMatched(
        ServerRequestContract $request,
        RouteContract $route,
        RouteMatchedHandlerContract $handler
    ): RouteContract|ResponseContract {
        if ($request->getHeaders()->getHeaderLine('Authorization') === '') {
            return new RedirectResponse(new Uri(path: '/login'));
        }

        return $handler->routeMatched($request, $route);
    }
}
```

The other stage contracts follow the same shape with their own arguments:

- **`RequestReceived`** — runs before route matching.
- **`RouteMatched`** — runs after the match, before the dispatch.
- **`RouteNotMatched`** — runs when the router returns a 404 or a 405.
- **`RouteDispatched`** — runs after the route handler returns.
- **`ThrowableCaught`** — runs when a throwable is caught.
- **`SendingResponse`** — runs before the response is written to the output.
- **`ResponseSent`** — runs after the client received the response.

The `RequestReceived` stage can also return a modified request, and the
`RouteMatched` stage a modified route. The `ResponseSent` stage returns nothing
— it is the place for deferred work.

Every stage is configurable globally through a class-string array on
`HttpConfig`. Five stages are also configurable per route, through the matching
arguments of `#[Route]`. The stage handlers resolve each class-string from the
container.

| Stage             | `HttpConfig` property       | Per-route argument          | Default middleware                                              |
| ----------------- | --------------------------- | --------------------------- | --------------------------------------------------------------- |
| `RequestReceived` | `requestReceivedMiddleware` | —                           | —                                                               |
| `RouteMatched`    | `routeMatchedMiddleware`    | `routeMatchedMiddleware`    | —                                                               |
| `RouteNotMatched` | `routeNotMatchedMiddleware` | —                           | `ViewRouteNotMatchedMiddleware`                                 |
| `RouteDispatched` | `routeDispatchedMiddleware` | `routeDispatchedMiddleware` | —                                                               |
| `ThrowableCaught` | `throwableCaughtMiddleware` | `throwableCaughtMiddleware` | `LogThrowableCaughtMiddleware`, `ViewThrowableCaughtMiddleware` |
| `SendingResponse` | `sendingResponseMiddleware` | `sendingResponseMiddleware` | —                                                               |
| `ResponseSent`    | `responseSentMiddleware`    | `responseSentMiddleware`    | —                                                               |

The router returns a 404 response when no route matches the path. It returns a
405 response when the path matches a route under `RequestMethod::ANY` but not
under the requested method. Both responses pass through the `RouteNotMatched`
stage.

## Response caching

`CacheResponseMiddleware` stores full responses on disk and replays them.
It implements two contracts, and both registrations are required: the
`RequestReceived` side reads the cache, and the `ResponseSent` side writes it.

```php
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;

$config = new HttpConfig(
    requestReceivedMiddleware: [CacheResponseMiddleware::class],
    responseSentMiddleware:    [CacheResponseMiddleware::class],
);
```

The container publisher constructs the middleware. It takes the cache directory
from `HttpServerConfig::$responseCacheFilePath` — with the framework's storage
cache path as the fallback — and `debug` from the application's debug mode.

The cache key is an MD5 hash of the request path and the request method. Each
cache file holds the response as JSON. An entry expires after 1800 seconds. A
response with a 5xx status code is never cached. In debug mode the middleware
still writes the cache, but it never reads it. A cache hit returns before route
matching, so only the `SendingResponse` and `ResponseSent` stages still run.

To keep a sensitive route out of the cache, add `NoCacheResponseMiddleware` to
that route. It sets the `Cache-Control`, `Pragma`, and `Expires` headers:

```php
use Valkyrja\Http\Server\Middleware\SendingResponse\NoCacheResponseMiddleware;

#[Route(path: '/account', name: 'account.show', sendingResponseMiddleware: [NoCacheResponseMiddleware::class])]
public function account(): ResponseContract
{
    return new JsonResponse(['account' => []]);
}
```

## HttpResponseException

Prefer to return a response with the wanted status code. To fail from deep
inside application code, throw an `HttpResponseException`:

```php
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Throwable\Exception\HttpResponseException;

throw new HttpResponseException(StatusCode::NOT_FOUND, 'Resource not found.');
```

The `RequestHandler` catches every throwable. In debug mode it rethrows the
throwable. Otherwise, for an `HttpResponseException` it uses the attached
response when one is set; without one it builds a generic body with the
exception's status code and a trace code. Any other throwable produces a
generic 500 response. The `ThrowableCaught` middleware then runs and can replace
the response, and the `SendingResponse` and `ResponseSent` stages still follow.

## Request lifecycle

```mermaid
flowchart TD
    A([Http::run]) --> B[Bootstrap - build ServerRequest from globals]
    B --> C[RequestReceived middleware]
    C -->|"cache hit / short-circuit"| G[SendingResponse middleware]
    C -->|throwable| J[ThrowableCaught middleware]
    C --> D{"Router: route matched?"}
    D -->|"no match: 404 / wrong method: 405"| E[RouteNotMatched middleware]
    D -->|matched| F[RouteMatched middleware]
    E -->|throwable| J
    E --> G
    F -->|"short-circuit"| G
    F -->|throwable| J
    F --> H[Route handler callable]
    H -->|throwable| J
    H --> I[RouteDispatched middleware]
    I -->|throwable| J
    I --> G
    J --> G
    G --> K[Send the response, close the session, finish the request]
    K --> L[ResponseSent middleware]
    L --> M([Process ends])
```
