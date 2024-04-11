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

namespace Valkyrja\Model\Models;

use UnitEnum;
use Valkyrja\Routing\Message;

use function assert;

/**
 * Trait MessageCapable.
 *
 * @author Melech Mizrachi
 */
trait MessageCapable
{
    /**
     * @inheritDoc
     */
    public static function fromMessage(string $message, array $data = []): static
    {
        assert(is_a($message, Message::class, true));

        $arr = [];
        /** @var UnitEnum[] $cases */
        $cases = $message::cases();

        // Convert the data from message value keyed to name keyed
        foreach ($cases as $case) {
            $arr[$case->name] = $data[$case->value ?? $case->name];
        }

        /** @var static $model */
        $model = static::fromArray($arr);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function asMessageArray(string $message, string ...$properties): array
    {
        assert(is_a($message, Message::class, true));

        $arr     = [];
        $asArray = $this->asArray(...$properties);
        /** @var UnitEnum[] $cases */
        $cases = $message::cases();

        // Convert the data from name keyed to message value keyed
        foreach ($cases as $case) {
            $arr[$case->name] = $asArray[$case->value ?? $case->name];
        }

        return $arr;
    }
}
