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

use Valkyrja\Type\Uid\Throwable\Exception\UidInvalidFromValueException;
use Valkyrja\Type\Ulid\Throwable\Contract\UlidThrowable;

class UlidInvalidFromValueException extends UidInvalidFromValueException implements UlidThrowable
{
}
