<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Struct;

use Override;
use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Request\Trait\JsonRequestStruct;
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;

/**
 * Struct TestIndexedJsonRequestStruct.
 */
enum IndexedJsonRequestStructEnum: int implements RequestStructContract
{
    use JsonRequestStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(JsonServerRequestContract|ServerRequestContract $request): array
    {
        self::ensureJsonRequest($request);

        $parsedJson = $request->getParsedJson();

        $first  = $parsedJson->get(self::first->value);
        $second = $parsedJson->get(self::second->value);
        $third  = $parsedJson->get(self::third->value);

        return [
            self::first->name  => [
                new Required($first, errorMessage: ErrorMessage::REQUIRED),
                new NotEmpty($first, errorMessage: ErrorMessage::IS_NOT_EMPTY),
            ],
            self::second->name => [
                new IsNumeric($second, errorMessage: ErrorMessage::IS_NUMERIC),
            ],
            self::third->name  => [
                new IsString($third, errorMessage: ErrorMessage::IS_STRING),
            ],
        ];
    }
}
