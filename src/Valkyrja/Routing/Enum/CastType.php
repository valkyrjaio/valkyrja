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

namespace Valkyrja\Routing\Enum;

use JsonSerializable;
use Valkyrja\Type\BuiltIn\ArrayT;
use Valkyrja\Type\BuiltIn\BoolT;
use Valkyrja\Type\BuiltIn\DoubleT;
use Valkyrja\Type\BuiltIn\FalseT;
use Valkyrja\Type\BuiltIn\FloatT;
use Valkyrja\Type\BuiltIn\IntT;
use Valkyrja\Type\BuiltIn\NullT;
use Valkyrja\Type\BuiltIn\ObjectT;
use Valkyrja\Type\BuiltIn\SerializedObject;
use Valkyrja\Type\BuiltIn\StringT;
use Valkyrja\Type\BuiltIn\TrueT;
use Valkyrja\Type\Json\Json;
use Valkyrja\Type\Json\JsonObject;

/**
 * Enum CastType.
 *
 * @author Melech Mizrachi
 */
enum CastType: string implements JsonSerializable
{
    case string            = StringT::class;
    case int               = IntT::class;
    case float             = FloatT::class;
    case double            = DoubleT::class;
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
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
