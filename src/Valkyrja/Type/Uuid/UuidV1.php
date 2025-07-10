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

namespace Valkyrja\Type\Uuid;

use Exception;
use Override;
use Valkyrja\Type\Exception\InvalidArgumentException;
use Valkyrja\Type\Type;
use Valkyrja\Type\Uuid\Contract\UuidV1 as Contract;
use Valkyrja\Type\Uuid\Support\UuidV1 as Helper;

use function gettype;
use function is_string;
use function sprintf;

/**
 * Class UuidV1.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class UuidV1 extends Type implements Contract
{
    /**
     * @throws Exception
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            Helper::validate($subject);
        }

        $this->subject = $subject
            ?? Helper::generate();
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if ($value !== null && ! is_string($value)) {
            throw new InvalidArgumentException(sprintf('String or null expected value of type `%s` provided', gettype($value)));
        }

        return new static($value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string
    {
        return $this->asValue();
    }
}
