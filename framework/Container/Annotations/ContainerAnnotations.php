<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Contracts\Container\Annotations\ContainerAnnotations as ContainerAnnotationsContract;

/**
 * Class ContainerAnnotations
 *
 * @package Valkyrja\Container\Annotations
 *
 * @author  Melech Mizrachi
 */
class ContainerAnnotations extends Annotations implements ContainerAnnotationsContract
{
    /**
     * @var string
     */
    protected $servicesAnnotationType = 'Service';

    /**
     * @var string
     */
    protected $aliasServicesAnnotationType = 'ServiceAlias';

    /**
     * @var string
     */
    protected $contextServicesAnnotationType = 'ServiceContext';

    /**
     * Get the services.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Container\Service[]
     *
     * @throws \ReflectionException
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->contextServicesAnnotationType, ...$classes);
    }

    /**
     * Get the alias services.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Container\ServiceContext[]
     *
     * @throws \ReflectionException
     */
    public function getAliasServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->aliasServicesAnnotationType, ...$classes);
    }

    /**
     * Get the context services.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Container\ServiceContext[]
     *
     * @throws \ReflectionException
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->contextServicesAnnotationType, ...$classes);
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string   $type       The type
     * @param string[] ...$classes The classes
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    protected function getAllClassesAnnotationsByType(string $type, string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            foreach ($this->classAndMembersAnnotationsType($type, $class) as $annotation) {
                // Set the type to null (we already know it's a service)
                $annotation->setType();
                // TODO: Get dependencies
                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }
}
