<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Ulid\Throwable\Exception;

use Valkyrja\Type\Throwable\Exception\Abstract\TypeRuntimeException;
use Valkyrja\Type\Ulid\Throwable\Contract\UlidThrowable;

class UlidRandomBytesFailureException extends TypeRuntimeException implements UlidThrowable
{
}
