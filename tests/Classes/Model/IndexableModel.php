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

use Valkyrja\Model\IndexedModel as Contract;
use Valkyrja\Model\Models\Indexable;
use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test Indexable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class IndexableModel extends AbstractModel implements Contract
{
    use Indexable;
    use PrivateProperty;

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
            Model::PUBLIC    => 1,
            Model::PROTECTED => 2,
            Model::PRIVATE   => 3,
            Model::NULLABLE  => 4,
        ];
    }
}
