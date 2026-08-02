<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Broadcast\Throwable\Exception\Abstract;

use Valkyrja\Broadcast\Throwable\Contract\BroadcastThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class BroadcastInvalidArgumentException extends ValkyrjaInvalidArgumentException implements BroadcastThrowable
{
}
