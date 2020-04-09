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

namespace Valkyrja\ORM\Enums;

/**
 * Enum Statement.
 */
final class Statement extends \Valkyrja\Support\Enum\Enum
{
    public const SELECT    = 'SELECT';
    public const INSERT    = 'INSERT';
    public const INTO      = 'INTO';
    public const UPDATE    = 'UPDATE';
    public const DELETE    = 'DELETE';
    public const JOIN      = 'JOIN';
    public const INNER     = 'INNER';
    public const OUTER     = 'OUTER';
    public const LEFT      = 'LEFT';
    public const RIGHT     = 'RIGHT';
    public const COUNT     = 'COUNT';
    public const DISTINCT  = 'DISTINCT';
    public const ON        = 'ON';
    public const AS        = 'AS';
    public const FROM      = 'FROM';
    public const SET       = 'SET';
    public const VALUE     = 'VALUES';
    public const WHERE     = 'WHERE';
    public const WHERE_AND = 'AND';
    public const WHERE_OR  = 'OR';
    public const GROUP_BY  = 'GROUP BY';
    public const ORDER_BY  = 'ORDER BY';
    public const LIMIT     = 'LIMIT';
    public const OFFSET    = 'OFFSET';

    protected static ?array $VALUES = [
        self::SELECT,
        self::INSERT,
        self::INTO,
        self::UPDATE,
        self::DELETE,
        self::JOIN,
        self::INNER,
        self::OUTER,
        self::LEFT,
        self::RIGHT,
        self::COUNT,
        self::DISTINCT,
        self::ON,
        self::AS,
        self::FROM,
        self::SET,
        self::VALUE,
        self::WHERE,
        self::WHERE_AND,
        self::WHERE_OR,
        self::GROUP_BY,
        self::ORDER_BY,
        self::LIMIT,
        self::OFFSET,
    ];
}
