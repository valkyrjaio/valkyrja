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

namespace Valkyrja\ORM\Support;

use function date;
use function str_replace;

/**
 * Class Helpers.
 *
 * @author Melech Mizrachi
 */
class Helpers
{
    /**
     * Get a combined table.column string.
     *
     * @param string $table  The table
     * @param string $column The column
     *
     * @return string
     */
    public static function getTableColumn(string $table, string $column): string
    {
        return "$table.$column";
    }

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
     * @return string
     */
    public static function getFormattedDate(): string
    {
        return date('Y-m-d H:i:s T');
    }
}
