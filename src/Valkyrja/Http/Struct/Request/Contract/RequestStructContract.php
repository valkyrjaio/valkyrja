<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Request\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Contract\StructContract;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;

interface RequestStructContract extends StructContract
{
    /**
     * Get the validation rules.
     *
     * <code>
     *      return [
     *          self::title->name => [
     *              new Required($title = $request->getParsedBody()->get(self::title->name)),
     *              new NotEmpty($title),
     *          ],
     *      ]
     * </code>
     *
     * @return array<non-empty-string, RuleContract[]>
     */
    public static function getValidationRules(ServerRequestContract $request): array;

    /**
     * Validate the Struct.
     */
    public static function validate(ServerRequestContract $request): ValidatorContract;

    /**
     * Get the data from a given request.
     *
     * @param ServerRequestContract $request The request
     *
     * @return array<array-key, mixed>
     */
    public static function getDataFromRequest(ServerRequestContract $request): array;

    /**
     * Determine if a request has extra data that was passed that is not defined in the struct.
     */
    public static function determineIfRequestContainsExtraData(ServerRequestContract $request): bool;
}
