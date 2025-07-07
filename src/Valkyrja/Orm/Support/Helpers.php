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

namespace Valkyrja\Orm\Support;

use DateTime;
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Exception\RuntimeException;

use function microtime;
use function str_replace;

/**
 * Class Helpers.
 *
 * @author Melech Mizrachi
 */
class Helpers
{
    /**
     * Get a column for a value bind.
     *
     * @param string $column The column
     *
     * @return string
     */
    public static function getColumnForValueBind(string $column): string
    {
        return ':'
            . str_replace(
                [
                    '.',
                    ':',
                    '-',
                ],
                '',
                $column
            );
    }

    /**
     * Get the formatted date.
     *
     * @param string $format [optional] The format
     *
     * @return string
     */
    public static function getFormattedDate(string $format = DateFormat::DEFAULT): string
    {
        $dateTime = DateTime::createFromFormat('U.u', (string) microtime(true));

        if ($dateTime === false) {
            throw new RuntimeException('Failure occurred when creating a new DateTime object for current microtime.');
        }

        return $dateTime->format($format);
    }
}
