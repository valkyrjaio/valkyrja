<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Annotations;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Events\Annotations\ListenerAnnotations as ListenerAnnotationsContract;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Support\Provides;

/**
 * Class ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
class ListenerAnnotations extends Annotations implements ListenerAnnotationsContract
{
    use Provides;

    /**
     * Get the events.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Events\Listener[]
     */
    public function getListeners(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Dispatcher\Dispatch $annotation */
            foreach ($this->methodsAnnotationsType('Listener', $class) as $annotation) {
                $this->setListenerProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a listener annotation.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    protected function setListenerProperties(Dispatch $dispatch): void
    {
        $classReflection = $this->getClassReflection($dispatch->getClass());

        if ($dispatch->getMethod() || $classReflection->hasMethod('__construct')) {
            $methodReflection = $this->getMethodReflection(
                $dispatch->getClass(),
                $dispatch->getMethod() ?? '__construct'
            );
            $parameters       = $methodReflection->getParameters();

            // Set the dependencies
            $dispatch->setDependencies($this->getDependencies(...$parameters));
        }

        $dispatch->setMatches();
        $dispatch->setAnnotationArguments();
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::LISTENER_ANNOTATIONS,
        ];
    }

    /**
     * Bind the listener annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LISTENER_ANNOTATIONS,
            new ListenerAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }
}
