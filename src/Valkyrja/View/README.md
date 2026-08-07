# View

## Introduction

The View component renders templates and produces HTTP responses from them. It
supports three rendering backends — pure PHP, Orka (Valkyrja's own compiled
template syntax), and Twig — and exposes a uniform `RendererContract` interface
across all three. Switching renderers requires only a configuration change; the
template code that calls the renderer does not change.

## Renderers

The active renderer is resolved from the container as
`Valkyrja\View\Renderer\Contract\RendererContract`. The three concrete
implementations are `PhpRenderer`, `OrkaRenderer`, and `TwigRenderer`.

```php
public function render(string $name, array $variables = []): string;
public function renderFile(string $name, array $variables = []): string;
public function createTemplate(string $name, array $variables = []): TemplateContract;
public function startRender(): void;
public function endRender(): string;
```

`render()` resolves a template by name relative to the configured view directory
and returns the rendered string. `renderFile()` resolves by file path.
`createTemplate()` returns a `TemplateContract` instance for more granular
control over rendering.

## Templates

`Valkyrja\View\Template\Contract\TemplateContract` provides the full template
API:

```php
// Variables
public function getVariables(): array;
public function setVariable(string $key, mixed $value): static;
public function getVariable(string $key): mixed;

// Output escaping
public function escape(string|int|float $value): string;

// Layouts
public function setLayout(string $layout): static;
public function withoutLayout(): static;

// Blocks (for layout inheritance)
public function startBlock(string $name): void;
public function endBlock(): void;
public function getBlock(string $name): string;
public function hasBlock(string $name): bool;

// Partials
public function getPartial(string $partial, array $variables = []): string;

// Rendering
public function render(): string;
public function __toString(): string;
```

Templates are created via `RendererContract::createTemplate()` and are the
objects available inside PHP and Orka template files as `$template`.

## PHP Renderer

The PHP renderer executes `.phtml` files directly. Template variables are
extracted into the local scope via `extract()`, so
`$template->setVariable('user', $user)` becomes `$user` within the template
file.

Configure the renderer that the container binds through `ViewConfigContract`:

| Property          | Default              | Description                                    |
| :---------------- | :------------------- | :--------------------------------------------- |
| `defaultRenderer` | `PhpRenderer::class` | The renderer class bound to `RendererContract` |

Configure the PHP renderer through `ViewPhpConfigContract`:

| Property           | Default            | Description                             |
| :----------------- | :----------------- | :-------------------------------------- |
| `phpPath`          | `/resources/views` | Directory containing PHP template files |
| `phpFileExtension` | `.phtml`           | File extension for PHP templates        |
| `phpPaths`         | `[]`               | Additional named path aliases           |

## Orka Renderer

The Orka renderer compiles `.orka.phtml` files to PHP and caches the compiled
output in `storage/views/`. Compilation happens on first access; subsequent
requests use the cached PHP file. When `debugMode` is enabled, the cache is
always regenerated.

### Register the Replacement Provider

Warning: the Orka renderer needs `ViewOrkaServiceProvider`. The renderer reads
each replacement from the container, and the container builds nothing that a
binding does not describe. Register the provider in the application that uses
Orka:

```php
use Valkyrja\View\Provider\ViewOrkaServiceProvider;

$container->register(new ViewOrkaServiceProvider());
```

`ViewComponentProvider` does not register it. Orka ships 38 replacements, and an
application that renders with `PhpRenderer` or `TwigRenderer` carries none of
them.

Bind each replacement that `orkaReplacements` adds. The provider binds the core
set only.

Configure the Orka renderer through `ViewOrkaConfigContract`:

| Property               | Default            | Description                              |
| :--------------------- | :----------------- | :--------------------------------------- |
| `orkaPath`             | `/resources/views` | Directory containing Orka template files |
| `orkaFileExtension`    | `.orka.phtml`      | File extension for Orka templates        |
| `orkaPaths`            | `[]`               | Additional named path aliases            |
| `orkaCoreReplacements` | all built-ins      | Core replacement classes to load         |
| `orkaReplacements`     | `[DEBUG]`          | Additional replacement classes           |

### Orka Syntax Reference

Orka templates use `@directive` syntax for control flow and layout composition,
and `{{ }}` for output.

**Output**

| Syntax              | Compiles to                                   |
| :------------------ | :-------------------------------------------- |
| `{{ $variable }}`   | HTML-escaped output via `$template->escape()` |
| `{{{ $variable }}}` | Raw unescaped output                          |
| `@set($key, $val)`  | `$template->setVariable($key, $val)`          |

**Conditionals**

```
@if ($condition)
@elseif ($condition)
@else
@endif

@unless ($condition)   // equivalent to @if (!$condition)
@elseunless ($condition)
@endunless

@isset ($variable)
@empty ($variable)
@notempty ($variable)
```

**Loops**

```
@foreach ($items as $item)
@endforeach

@for ($i = 0; $i < 10; $i++)
@endfor

@break
```

**Switch**

```
@switch ($value)
    @case ('foo')
    @default
@endswitch
```

**Layouts and blocks**

```
@layout ('layouts/app')          // set the parent layout

@block ('content')               // output a block (or its default)
@startblock ('content')          // begin capturing block content
@endblock                        // end capturing
@trimblock ('content')           // output block with whitespace trimmed

@has ('blockName')               // true if block has content
@elsehas ('blockName')
@endhas
```

**Partials**

```
@partial ('partials/header')
@partial_with_variables ('partials/nav', $variables)
```

**Comments**

```
@! This is a single-line comment
@!* This is a
    multi-line comment *@
```

**Debug**

```
@dd ($variable)                  // dump and die
```

## Twig Renderer

The Twig renderer delegates to a configured `Twig\Environment` instance. All
Twig syntax and extensions work as normal.

Configure the Twig renderer through `ViewTwigConfigContract`:

| Property           | Default          | Description                           |
| :----------------- | :--------------- | :------------------------------------ |
| `twigPaths`        | `[]`             | Namespace-to-directory path map       |
| `twigExtensions`   | `[]`             | Extension class names to add to Twig  |
| `twigCompiledPath` | `/storage/views` | Directory for compiled Twig templates |

Your application config class implements only the contracts for the renderers
that it uses. Each renderer contract prefixes its properties with the renderer
name, so one class can implement several of them at once.

## Producing HTTP Responses

`Valkyrja\View\Factory\Contract\ViewResponseFactoryContract` creates PSR-7 HTTP
responses directly from a template name:

```php
public function createResponseFromView(
    string $template,
    array $data = [],
    StatusCode $statusCode = StatusCode::OK,
    HeaderCollectionContract|null $headers = null
): ResponseContract;
```

Resolve it from the container and use it in your controllers to return view
responses without manually constructing the response object:

```php
$response = $responseFactory->createResponseFromView('users/show', ['user' => $user]);
```

## Service Registration

The View component registers the following singletons:

| Contract / Class              | Description                                  |
| :---------------------------- | :------------------------------------------- |
| `ViewConfigContract`          | Component config                             |
| `ViewPhpConfigContract`       | PHP renderer config                          |
| `ViewOrkaConfigContract`      | Orka renderer config                         |
| `ViewTwigConfigContract`      | Twig renderer config                         |
| `RendererContract`            | The active renderer (default: `PhpRenderer`) |
| `PhpRenderer`                 | PHP renderer instance                        |
| `OrkaRenderer`                | Orka renderer instance                       |
| `TwigRenderer`                | Twig renderer instance                       |
| `Twig\Environment`            | Configured Twig environment                  |
| `ViewResponseFactoryContract` | View response factory                        |

Register the View service provider by including it in your component provider's
`getContainerProviders()` return value.
