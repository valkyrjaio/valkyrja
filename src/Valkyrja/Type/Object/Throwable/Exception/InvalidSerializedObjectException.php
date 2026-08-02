<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Object\Throwable\Exception;

use Valkyrja\Type\Object\Throwable\Contract\ObjectThrowable;
use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;

class InvalidSerializedObjectException extends TypeInvalidArgumentException implements ObjectThrowable
{
}
