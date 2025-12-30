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
use Valkyrja\Type\Model\Contract\ExposableIndexedModel as Contract;
use Valkyrja\Type\Model\Model as AbstractModel;
use Valkyrja\Type\Model\Trait\ExposableIndexable;

/**
 * Model class to use to test Indexable model.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ExposedIndexableModelClass extends AbstractModel implements Contract
{
    use ExposableIndexable;
    use PrivatePropertyTrait;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getIndexes(): array
    {
        return IndexableModelClass::getIndexes();
    }

    /**
     * @inheritDoc
     */
    public static function getExposable(): array
    {
        return ExposableModelClass::getExposable();
    }
}
