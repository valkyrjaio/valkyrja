<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uid\Throwable\Exception;

use Valkyrja\Type\Throwable\Exception\Abstract\TypeInvalidArgumentException;
use Valkyrja\Type\Uid\Throwable\Contract\UidThrowable;

class UidInvalidFromValueException extends TypeInvalidArgumentException implements UidThrowable
{
}
