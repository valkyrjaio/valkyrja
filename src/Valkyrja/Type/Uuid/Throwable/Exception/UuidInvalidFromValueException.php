<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uuid\Throwable\Exception;

use Valkyrja\Type\Uid\Throwable\Exception\UidInvalidFromValueException;
use Valkyrja\Type\Uuid\Throwable\Contract\UuidThrowable;

class UuidInvalidFromValueException extends UidInvalidFromValueException implements UuidThrowable
{
}
