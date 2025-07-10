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

namespace Valkyrja\Type\BuiltIn;

use Override;
use Valkyrja\Type\BuiltIn\Contract\BoolT as Contract;
use Valkyrja\Type\Type;

/**
 * Class BoolT.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<bool>
 */
class BoolT extends Type implements Contract
{
    public function __construct(bool $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static((bool) $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): bool
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): bool
    {
        return $this->asValue();
    }
}
