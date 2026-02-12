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
    public static function getValidationRules(ServerRequestContract $request): array|null
    {
        $parsedBody = $request->getParsedBody();

        $first  = $parsedBody->getParam(self::first->value);
        $second = $parsedBody->getParam(self::second->value);
        $third  = $parsedBody->getParam(self::third->value);

        return [
            self::first->name  => [
                new Required($first),
                new NotEmpty($first),
            ],
            self::second->name => [
                new IsNumeric((int) $second),
            ],
            self::third->name  => [
                new IsString($third),
            ],
        ];
    }
}
