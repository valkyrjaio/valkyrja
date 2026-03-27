# The Dispatcher

## Introduction

The Dispatcher is one of the most foundational components in Valkyrja. It is the
engine that powers event dispatching, CLI command execution, and HTTP route
handling — any situation where the framework needs to invoke a callable, a
method on a class, or a class itself in response to something that has occurred.

Understanding the Dispatcher makes the entire framework more legible. Once you
see how it works in the context of events, you will immediately recognise the
same pattern operating in the CLI and HTTP layers.

## What the Dispatcher Does

At its core, the Dispatcher resolves and invokes a **dispatch** — a description
of what to call and how to call it. Rather than calling code directly, Valkyrja
describes the call as a data object and hands it to the Dispatcher, which
handles resolution and invocation. This indirection is what makes deferred
loading, caching, and the data class generation system possible.

## Dispatch Types

Valkyrja supports several dispatch types, each describing a different kind of
callable:

**ClassDispatch** — Instructs the Dispatcher to resolve the class from the
container and invoke it directly (calling the class as an invokable, or using a
default invocation method). Used when the entire class represents a single
callable unit of work.

**MethodClassDispatch** — Instructs the Dispatcher to resolve the class from the
container and invoke a specific named method on it. Used when a class contains
multiple handlers or when a particular method is the logical handler for a
specific event, command, or route.

Both dispatch types resolve their target through the container, meaning the full
power of Valkyrja's dependency injection — deferred loading, singleton
management, service binding — is available to every dispatched handler.

## How It Connects to Events, CLI, and HTTP

The same Dispatcher underpins all three runtime contexts:

- **Events** — When an event is fired, the Dispatcher invokes each registered
  listener's dispatch, passing the event object as the argument.
- **CLI** — When a command is matched, the Dispatcher invokes the command
  handler's dispatch, passing the parsed input.
- **HTTP** — When a route is matched, the Dispatcher invokes the route handler's
  dispatch, passing the request.

The pattern is identical in each context. Only the argument passed and the
surrounding lifecycle differ.

## Return Values

The Dispatcher returns whatever the invoked dispatch returns. In the context of
events, return values can be collected by the event if it implements
`DispatchCollectableContract`. In CLI and HTTP contexts, the return value
becomes the response or output that the kernel sends back to the caller.

## Why This Design

Describing calls as data objects rather than executing them directly enables the
framework's caching system. When the data cache is generated, the full set of
dispatch descriptions — for every event listener, every command, every route —
is captured in a generated PHP class. On subsequent requests, the framework
loads that class directly and the Dispatcher can invoke any handler without any
registration overhead.

This is a significant part of why Valkyrja is fast. The Dispatcher does very
little work per request when the cache is warm — it simply receives a dispatch
description and executes it.
