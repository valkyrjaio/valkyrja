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

namespace Valkyrja\Routing\Enums;

use JsonSerializable;
use Valkyrja\Type\Types\ArrayT;
use Valkyrja\Type\Types\BoolT;
use Valkyrja\Type\Types\DoubleT;
use Valkyrja\Type\Types\FalseT;
use Valkyrja\Type\Types\FloatT;
use Valkyrja\Type\Types\IntT;
use Valkyrja\Type\Types\JsonObject;
use Valkyrja\Type\Types\Json;
use Valkyrja\Type\Types\NullT;
use Valkyrja\Type\Types\ObjectT;
use Valkyrja\Type\Types\SerializedObject;
use Valkyrja\Type\Types\StringT;
use Valkyrja\Type\Types\TrueT;

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
