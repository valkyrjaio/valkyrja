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
use Valkyrja\Type\Model\Abstract\Model as AbstractModel;
use Valkyrja\Type\Model\Contract\IndexedModelContract as Contract;
use Valkyrja\Type\Model\Trait\Indexable;

/**
 * Model class to use to test Indexable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class IndexableModelClass extends AbstractModel implements Contract
{
    use Indexable;
    use PrivatePropertyTrait;

    public const PUBLIC_INDEX    = 1;
    public const PROTECTED_INDEX = 2;
    public const PRIVATE_INDEX   = 3;
    public const NULLABLE_INDEX  = 4;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getIndexes(): array
    {
        return [
            ModelClass::PUBLIC    => 1,
            ModelClass::PROTECTED => 2,
            ModelClass::PRIVATE   => 3,
            ModelClass::NULLABLE  => 4,
        ];
    }
}
