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

namespace Valkyrja\Type\Enum;

use JsonSerializable;
use Override;
use Valkyrja\Type\Array\ArrayT;
use Valkyrja\Type\Bool\BoolT;
use Valkyrja\Type\Bool\FalseT;
use Valkyrja\Type\Bool\TrueT;
use Valkyrja\Type\Float\FloatT;
use Valkyrja\Type\Int\IntT;
use Valkyrja\Type\Json\Json;
use Valkyrja\Type\Json\JsonObject;
use Valkyrja\Type\Null\NullT;
use Valkyrja\Type\Object\ObjectT;
use Valkyrja\Type\Object\SerializedObject;
use Valkyrja\Type\String\StringT;

enum CastType: string implements JsonSerializable
{
    case string            = StringT::class;
    case int               = IntT::class;
    case float             = FloatT::class;
    case bool              = BoolT::class;
    case array             = ArrayT::class;
    case object            = ObjectT::class;
    case serialized_object = SerializedObject::class;
    case json              = Json::class;
    case json_object       = JsonObject::class;
    case true              = TrueT::class;
    case false             = FalseT::class;
    case null              = NullT::class;

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
