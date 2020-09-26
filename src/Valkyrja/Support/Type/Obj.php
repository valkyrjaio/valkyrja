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

use function count;
use function explode;
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

    /**
     * Convert an object to an array.
     *
     * @param object $subject The subject object
     *
     * @return array
     */
    public static function toArray(object $subject): array
    {
        // The subject cast as an array
        $castSubject = (array) $subject;
        // The array to return
        $array = [];

        // Iterate through each subject
        foreach ($castSubject as $key => $value) {
            // Explode the key on the \0 character
            /*
             * Public members: member_name
             * Protected members: \0*\0member_name
             * Private members: \0Class_name\0member_name
             */
            $keyParts = explode("\0", $key);
            // Use the last key part as the property name
            $property = $keyParts[count($keyParts) - 1];
            // Set the property and value
            $array[$property] = $value;
        }

        return $array;
    }

    /**
     * Convert an object to a deep array.
     *
     * @param object $subject The subject object
     *
     * @throws JsonException
     *
     * @return array
     */
    public static function toDeepArray(object $subject): array
    {
        return Arr::fromString(static::toString($subject));
    }
}
