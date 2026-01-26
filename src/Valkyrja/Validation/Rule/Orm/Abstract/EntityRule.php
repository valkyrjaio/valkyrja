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

namespace Valkyrja\Validation\Rule\Orm\Abstract;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Validation\Rule\Abstract\Rule;

abstract class EntityRule extends Rule
{
    /**
     * @param class-string<EntityContract> $entity       The entity to match
     * @param non-empty-string|null        $field        The field to match
     * @param non-empty-string|null        $errorMessage The error message
     */
    public function __construct(
        protected ManagerContract $orm,
        string|int|float|bool $subject,
        protected string $entity,
        protected string|null $field = null,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    protected function checkForEntity(): EntityContract|null
    {
        /** @var string|int|float|bool $subject */
        $subject = $this->subject;
        $entity  = $this->entity;
        $field   = $this->field;

        $field ??= $entity::getIdField();

        // Check for a result
        return $this->orm->createRepository($entity)->findBy(new Where(new Value(name: $field, value: $subject)));
    }
}
