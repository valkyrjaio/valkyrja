<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotations\Annotations;
use Valkyrja\Annotation\AnnotationsParser;
use Valkyrja\Application\Application;
use Valkyrja\Container\Service as ContainerService;
use Valkyrja\Container\ServiceContext as ContainerContextService;

/**
 * Class ContainerAnnotations.
 *
 * @author Melech Mizrachi
 */
class NativeContainerAnnotations extends Annotations implements ContainerAnnotations
{
    /**
     * The services annotation type.
     *
     * @var string
     */
    protected string $servicesAnnotationType = 'Service';

    /**
     * The service alias annotation type.
     *
     * @var string
     */
    protected string $aliasServicesAnnotationType = 'ServiceAlias';

    /**
     * The service context annotation type.
     *
     * @var string
     */
    protected string $contextServicesAnnotationType = 'ServiceContext';

    /**
     * Get the services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Service[]
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->servicesAnnotationType, ...$classes);
    }

    /**
     * Get the alias services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return ServiceContext[]
     */
    public function getAliasServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->aliasServicesAnnotationType, ...$classes);
    }

    /**
     * Get the context services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return ContainerContextService[]
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->contextServicesAnnotationType, ...$classes);
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string $type       The type
     * @param string ...$classes The classes
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
            /** @var Annotation $annotation */
            foreach ($this->classAndMembersAnnotationsType($type, $class) as $annotation) {
                $this->setServiceProperties($annotation);

                // If this annotation is a service
                if ($type === $this->servicesAnnotationType) {
                    /* @var Service $annotation */
                    $annotations[] = $this->getServiceFromAnnotation($annotation);

                    continue;
                }

                // If this annotation is a context service
                if ($type === $this->contextServicesAnnotationType) {
                    /* @var ServiceContext $annotation */
                    $annotations[] = $this->getServiceContextFromAnnotation($annotation);

                    continue;
                }

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
        if (null === $annotation->getProperty()) {
            $parameters =
                $this->getMethodReflection($annotation->getClass(), $annotation->getMethod() ?? '__construct')
                     ->getParameters();

            // Set the dependencies
            $annotation->setDependencies($this->getDependencies(...$parameters));
        }

        $annotation->setMatches();
    }

    /**
     * Get a service from a service annotation.
     *
     * @param Service $service The service annotation
     *
     * @return ContainerService
     */
    protected function getServiceFromAnnotation(Service $service): ContainerService
    {
        $containerService = new ContainerService();

        $containerService
            ->setDefaults($service->getDefaults())
            ->setSingleton($service->isSingleton())
            ->setId($service->getId())
            ->setName($service->getName())
            ->setClass($service->getClass())
            ->setProperty($service->getProperty())
            ->setMethod($service->getMethod())
            ->setStatic($service->isStatic())
            ->setFunction($service->getFunction())
            ->setMatches($service->getMatches())
            ->setDependencies($service->getDependencies())
            ->setArguments($service->getArguments());

        return $containerService;
    }

    /**
     * Get a service context from a service context annotation.
     *
     * @param ServiceContext $service The service context annotation
     *
     * @return ContainerContextService
     */
    protected function getServiceContextFromAnnotation(ServiceContext $service): ContainerContextService
    {
        $containerService = new ContainerContextService();

        $containerService
            ->setContextClass($service->getContextClass())
            ->setContextProperty($service->getContextProperty())
            ->setContextMethod($service->getContextMethod())
            ->setContextFunction($service->getContextFunction())
            ->setDefaults($service->getDefaults())
            ->setSingleton($service->isSingleton())
            ->setId($service->getId())
            ->setClass($service->getClass())
            ->setName($service->getName())
            ->setProperty($service->getProperty())
            ->setMethod($service->getMethod())
            ->setStatic($service->isStatic())
            ->setFunction($service->getFunction())
            ->setMatches($service->getMatches())
            ->setDependencies($service->getDependencies())
            ->setArguments($service->getArguments());

        return $containerService;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ContainerAnnotations::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            ContainerAnnotations::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
