<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Factory\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

interface ViewResponseFactoryContract
{
    /**
     * View response builder.
     *
     * @param non-empty-string               $template The view template to use
     * @param array<non-empty-string, mixed> $data     [optional] The view data
     */
    public function createResponseFromView(
        string $template,
        array $data = [],
        StatusCode $statusCode = StatusCode::OK,
        HeaderCollectionContract|null $headers = null
    ): ResponseContract;
}
