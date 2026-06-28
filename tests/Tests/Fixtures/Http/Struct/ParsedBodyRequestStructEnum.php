<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Http\Struct;

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
 * Struct TestParsedBodyRequestStruct.
 */
enum ParsedBodyRequestStructEnum implements RequestStructContract
{
    use ParsedBodyRequestStruct;

    case first;
    case second;
    case third;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getValidationRules(ServerRequestContract $request): array
    {
        $parsedBody = $request->getParsedBody();

        $first  = $parsedBody->get(self::first->name);
        $second = $parsedBody->get(self::second->name);
        $third  = $parsedBody->get(self::third->name);

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
