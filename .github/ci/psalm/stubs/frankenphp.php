<?php

declare(strict_types=1);

/*
 * Stubs for the FrankenPHP runtime functions, which are only available when
 * running under the FrankenPHP worker runtime. They let static analysis resolve
 * the OpenSwoole/RoadRunner-style worker entry point without the runtime
 * present. See https://frankenphp.dev/docs/worker/
 */

/**
 * Handle a single request in the FrankenPHP worker runtime.
 */
function frankenphp_handle_request(callable $callback): bool
{
}
