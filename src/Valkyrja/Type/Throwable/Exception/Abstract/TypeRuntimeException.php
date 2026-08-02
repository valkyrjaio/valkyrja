<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Throwable\Exception\Abstract;

use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;
use Valkyrja\Type\Throwable\Contract\TypeThrowable;

abstract class TypeRuntimeException extends ValkyrjaRuntimeException implements TypeThrowable
{
}
