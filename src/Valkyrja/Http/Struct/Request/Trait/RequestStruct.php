<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Type\Enum\Trait\Arrayable;
use Valkyrja\Validation\Rule\Contract\RuleContract;
use Valkyrja\Validation\Validator\Contract\ValidatorContract;
use Valkyrja\Validation\Validator\Validator;

trait RequestStruct
{
    use Arrayable;

    /**
     * @inheritDoc
     */
    public static function getDataFromRequest(ServerRequestContract $request): array
    {
        return static::getOnlyParamsFromRequest($request, ...static::values());
    }

    /**
     * @inheritDoc
     */
    public static function determineIfRequestContainsExtraData(ServerRequestContract $request): bool
    {
        return ! empty(static::getExceptParamsFromRequest($request, ...static::values()));
    }

    /**
     * @inheritDoc
     *
     * @return array<non-empty-string, RuleContract[]>
     */
    public static function getValidationRules(ServerRequestContract $request): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function validate(ServerRequestContract $request): ValidatorContract
    {
        return new Validator(static::getValidationRules($request));
    }

    /**
     * Get only the specified request params.
     *
     * @param ServerRequestContract $request   The request
     * @param string|int            ...$values The values
     *
     * @return array<array-key, mixed>
     */
    abstract protected static function getOnlyParamsFromRequest(ServerRequestContract $request, string|int ...$values): array;

    /**
     * Get all request params except the ones specified.
     *
     * @param ServerRequestContract $request   The request
     * @param string|int            ...$values The values
     *
     * @return array<array-key, mixed>
     */
    abstract protected static function getExceptParamsFromRequest(ServerRequestContract $request, string|int ...$values): array;
}
