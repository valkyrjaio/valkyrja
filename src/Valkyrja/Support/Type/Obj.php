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

namespace Valkyrja\Support\Type;

use JsonException;

use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Class Obj.
 *
 * @author Melech Mizrachi
 */
class Obj
{
    /**
     * Convert an object to a string.
     *
     * @param object $subject The subject object
     *
     * @throws JsonException
     *
     * @return string
     */
    public static function toString(object $subject): string
    {
        return json_encode($subject, JSON_THROW_ON_ERROR);
    }

    /**
     * Un-convert an object from a string.
     *
     * @param string $subject The subject object as a string
     *
     * @throws JsonException
     *
     * @return object
     */
    public static function fromString(string $subject): object
    {
        return json_decode($subject, false, 512, JSON_THROW_ON_ERROR);
    }
}
