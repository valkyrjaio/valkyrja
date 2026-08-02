<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Model;

use Valkyrja\Tests\Fixtures\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;

/**
 * Model class to use to test abstract model.
 *
 * @property string $protected
 */
final class ModelFixture extends Model
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
