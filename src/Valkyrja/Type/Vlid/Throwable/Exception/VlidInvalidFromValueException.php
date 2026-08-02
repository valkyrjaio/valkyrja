<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Vlid\Throwable\Exception;

use Valkyrja\Type\Uid\Throwable\Exception\UidInvalidFromValueException;
use Valkyrja\Type\Vlid\Throwable\Contract\VlidThrowable;

class VlidInvalidFromValueException extends UidInvalidFromValueException implements VlidThrowable
{
}
