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

use Valkyrja\Model\ExposableIndexedModel as Contract;
use Valkyrja\Model\Models\ExposableIndexable;
use Valkyrja\Model\Models\Model as AbstractModel;

/**
 * Model class to use to test Indexable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ExposedIndexableModel extends AbstractModel implements Contract
{
    use ExposableIndexable;
    use PrivateProperty;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getIndexes(): array
    {
        return IndexableModel::getIndexes();
    }

    /**
     * @inheritDoc
     */
    public static function getExposable(): array
    {
        return ExposableModel::getExposable();
    }
}
