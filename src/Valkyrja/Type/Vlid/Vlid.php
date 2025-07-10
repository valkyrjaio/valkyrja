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

namespace Valkyrja\Type\Vlid;

use Exception;
use Override;
use Valkyrja\Type\Exception\InvalidArgumentException;
use Valkyrja\Type\Type;
use Valkyrja\Type\Vlid\Contract\Vlid as Contract;
use Valkyrja\Type\Vlid\Support\Vlid as Helper;

use function gettype;
use function is_string;
use function sprintf;

/**
 * Class Vlid.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class Vlid extends Type implements Contract
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
