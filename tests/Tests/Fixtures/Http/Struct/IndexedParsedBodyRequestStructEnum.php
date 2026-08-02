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
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Request\Trait\ParsedBodyRequestStruct;
use Valkyrja\Validation\Constant\ErrorMessage;
use Valkyrja\Validation\Rule\Is\IsNumeric;
use Valkyrja\Validation\Rule\Is\IsString;
use Valkyrja\Validation\Rule\Is\NotEmpty;
use Valkyrja\Validation\Rule\Is\Required;

/**
 * Struct TestIndexedParsedBodyRequestStruct.
 */
enum IndexedParsedBodyRequestStructEnum: int implements RequestStructContract
{
    use ParsedBodyRequestStruct;

    case first  = 1;
    case second = 2;
    case third  = 3;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(ServerRequestContract $request): array
    {
        $parsedBody = $request->getParsedBody();

        $first  = $parsedBody->get(self::first->value);
        $second = $parsedBody->get(self::second->value);
        $third  = $parsedBody->get(self::third->value);

        return [
            self::first->name  => [
                new Required($first, errorMessage: ErrorMessage::REQUIRED),
                new NotEmpty($first, errorMessage: ErrorMessage::IS_NOT_EMPTY),
            ],
            self::second->name => [
                new IsNumeric((int) $second, errorMessage: ErrorMessage::IS_NUMERIC),
            ],
            self::third->name  => [
                new IsString($third, errorMessage: ErrorMessage::IS_STRING),
            ],
        ];
    }
}
