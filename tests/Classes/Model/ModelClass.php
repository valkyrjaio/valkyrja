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

namespace Valkyrja\Tests\Classes\Model;

use Valkyrja\Tests\Classes\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Model as AbstractModel;

/**
 * Model class to use to test abstract model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ModelClass extends AbstractModel
{
    use PrivatePropertyTrait;

    public const PUBLIC    = 'public';
    public const PROTECTED = 'protected';
    public const PRIVATE   = 'private';
    public const NULLABLE  = 'nullable';

    public const VALUES = [
        self::PUBLIC    => self::PUBLIC,
        self::NULLABLE  => null,
        self::PROTECTED => self::PROTECTED,
        self::PRIVATE   => self::PRIVATE,
    ];

    public string $public;

    public ?string $nullable;

    protected string $protected;
}
