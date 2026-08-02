<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\File\Throwable\Exception\Abstract;

use Valkyrja\Http\Message\File\Throwable\Contract\UploadedFileThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;

abstract class UploadedFileInvalidArgumentException extends HttpMessageInvalidArgumentException implements UploadedFileThrowable
{
}
