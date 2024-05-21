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

namespace Valkyrja\Container\Annotators;

use ReflectionException;
use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Container\Annotator as Contract;
use Valkyrja\Container\Enums\AnnotationName;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class ContainerAnnotator.
 *
 * @author Melech Mizrachi
 */
class Annotator implements Contract
{
    /**
     * ContainerAnnotator constructor.
     */
    public function __construct(
        protected Filter $filter,
        protected Reflection $reflection
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE, ...$classes);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getAliasServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE_ALIAS, ...$classes);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE_CONTEXT, ...$classes);
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string       $type       The type
     * @param class-string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function getAllClassesAnnotationsByType(string $type, string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            foreach ($this->filter->classAndMembersAnnotationsByType($type, $class) as $annotation) {
                $this->setServiceProperties($annotation);

                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a service annotation.
     *
     * @param Annotation $annotation
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setServiceProperties(Annotation $annotation): void
    {
        $class    = $annotation->getClass();
        $property = $annotation->getProperty();

        if ($class && $property === null) {
            $reflection = $this->reflection->forClassMethod(
                $class,
                $annotation->getMethod() ?? '__construct'
            );

            // Set the dependencies
            $annotation->setDependencies($this->reflection->getDependencies($reflection));
        }

        $annotation->setMatches();
    }
}
