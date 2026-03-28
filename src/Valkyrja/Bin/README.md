# Bin

## Introduction

The Bin component is the entry point for Valkyrja's console (CLI) binary. It
wires the framework's application bootstrap into a runnable command-line
executable, enabling the `valkyrja` console tool and any custom CLI commands
registered through the CLI component.

The component itself is intentionally thin — it provides a `ComponentProvider`
that participates in the standard component lifecycle and delegates all actual
command registration and dispatch to the CLI component.

## Usage

The Bin component is bootstrapped automatically when the application runs in a
console context. No direct interaction with this component is required; CLI
commands are defined and registered through the CLI component.

It has default commands that can help build an application quickly without
specific context of your application. If you need commands that have access to
your application's config you must add the command to the list of commands via a
cli route provider and run it via your own cli application.

```
php vendor/bin/valkyrja commandName [--option] [arguments]
```

## Service Registration

The Bin component registers no services of its own in the container. Its
`ComponentProvider` participates in the standard provider lifecycle, allowing
the framework to compose CLI tooling through the same provider mechanism used by
every other component.
