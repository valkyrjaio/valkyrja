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

namespace Valkyrja\ORM;

use Valkyrja\ORM\Enums\WhereType;

/**
 * Interface WhereQueryBuilder.
 *
 * @author Melech Mizrachi
 */
interface WhereQueryBuilder
{
    /**
     * Add a where condition to the query statement.
     * - Each additional use will add an `AND` where condition.
     *
     * <code>
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column');
     *      $queryBuilder
     *          ->select()
     *          ->table('table')
     *          ->where('column', '=', ':column')
     *          ->where('column2', '=', ':column2');
     * </code>
     *
     * @param string      $column
     * @param string|null $operator [optional]
     * @param mixed|null  $value    [optional]
     * @param bool        $setType  [optional]
     *
     * @return static
     */
    public function where(string $column, string $operator = null, mixed $value = null, bool $setType = true): self;

    /**
     * Start a where clause in parentheses.
     *
     * @return static
     */
    public function startWhereGroup(): self;

    /**
     * End a where clause in parentheses.
     *
     * @return static
     */
    public function endWhereGroup(): self;

    /**
     * Add a where type.
     *
     * @param WhereType $type The type
     *
     * @return static
     */
    public function whereType(WhereType $type = WhereType::AND): self;
}
