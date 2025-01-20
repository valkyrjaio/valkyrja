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

namespace Valkyrja\Type\Id;

use Valkyrja\Type\Id\Contract\IntId as Contract;
use Valkyrja\Type\Type;

/**
 * Class IntId.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<int>
 */
class IntId extends Type implements Contract
{
    public function __construct(int $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static((int) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): int
    {
        return $this->asValue();
    }
}
