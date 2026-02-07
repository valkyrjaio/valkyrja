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

namespace Valkyrja\Tests\Classes\Type\Model;

use Valkyrja\Tests\Classes\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;

/**
 * Model class to use to test abstract model.
 *
 * @property string $protected
 */
class ModelClass extends Model
{
    use PrivatePropertyTrait;

    public const string PUBLIC    = 'public';
    public const string PROTECTED = 'protected';
    public const string PRIVATE   = 'private';
    public const string NULLABLE  = 'nullable';

    /** @var array<string, string|null> */
    public const array VALUES = [
        self::PUBLIC    => self::PUBLIC,
        self::NULLABLE  => null,
        self::PROTECTED => self::PROTECTED,
        self::PRIVATE   => self::PRIVATE,
    ];

    public string $public;

    public string|null $nullable;

    protected string $protected;
}
