<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations;

use Valkyrja\Application;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Annotations.
 *
 * @author Melech Mizrachi
 */
class NativeAnnotationsParser implements AnnotationsParser
{
    use Provides;

    /**
     * The application.
     *
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * AnnotationsParser constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidAnnotationKeyArgument
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function getAnnotations(string $docString): array
    {
        $annotations = [];

        // Get all matches of @ Annotations
        $matches = $this->getAnnotationMatches($docString);

        // If there are any matches iterate through them and create new
        // annotations
        if ($matches && isset($matches[0], $matches[1])) {
            foreach ($matches[0] as $index => $match) {
                $this->setAnnotation($matches, $index, $annotations);
            }
        }

        return $annotations;
    }

    /**
     * Get annotation matches.
     *
     * @param string $docString The doc string
     *
     * @return array[]
     */
    protected function getAnnotationMatches(string $docString): ? array
    {
        preg_match_all($this->getRegex(), $docString, $matches);

        return $matches ?? null;
    }

    /**
     * Set a matched annotation.
     *
     * @param array $matches     The matches
     *                           [
     *                           0 => matches,
     *                           1 => annotation,
     *                           2 => type,
     *                           3 => args,
     *                           4 => var,
     *                           5 => desc
     *                           ]
     * @param int   $index       The index
     * @param array $annotations The annotations list
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidAnnotationKeyArgument
     *
     * @return void
     */
    protected function setAnnotation(array $matches, int $index, array &$annotations): void
    {
        $properties = $this->getAnnotationProperties($matches, $index);

        // Get the annotation model from the annotations map
        $annotation = $this->getAnnotationFromMap($properties['annotation']);

        // Set the annotation's type
        $annotation->setAnnotationType($properties['annotation']);

        // If there are arguments
        if (null !== $properties['arguments'] && $properties['arguments']) {
            // Filter the arguments and set them in the annotation
            $annotation->setAnnotationArguments(
                $this->getArguments($properties['arguments'])
            );

            // Having set the arguments there's no need to retain this key in
            // the properties
            unset($properties['arguments']);

            // Set the annotation's arguments to setters if they exist
            $this->setAnnotationArguments($annotation);

            // If all arguments have been set to their own properties in the
            // annotation model
            if (empty($annotation->getAnnotationArguments())) {
                // Set the arguments to null
                $annotation->setAnnotationArguments();
            }
        }

        // Set all the matches
        $annotation->setMatches($properties);

        // Set the annotation in the list
        $annotations[] = $annotation;
    }

    /**
     * Set the annotation's arguments.
     *
     * @param Annotation $annotation The annotation
     *
     * @return void
     */
    protected function setAnnotationArguments(Annotation $annotation): void
    {
        $arguments = $annotation->getAnnotationArguments();

        // Iterate through the arguments
        foreach ($annotation->getAnnotationArguments() as $key => $argument) {
            $methodName = 'set' . ucfirst($key);

            // Check if there is a setter function for this argument
            if (method_exists($annotation, $methodName)) {
                // Set the argument using the setter
                $annotation->{$methodName}($argument);

                // Unset from the arguments array
                unset($arguments[$key]);
            }
        }

        $annotation->setAnnotationArguments($arguments);
    }

    /**
     * Get the properties for a matched annotation.
     *
     * @param array $matches The matches
     * @param int   $index   The index
     *
     * @return array
     */
    protected function getAnnotationProperties(array $matches, int $index): array
    {
        $properties = [];

        // Written like this to appease the code coverage gods
        $properties['annotation']  = $matches[1][$index] ?? null;
        $properties['arguments']   = $matches[2][$index] ?? null;
        $properties['type']        = $matches[3][$index] ?? null;
        $properties['variable']    = $matches[4][$index] ?? null;
        $properties['description'] = $matches[5][$index] ?? null;

        return $this->processAnnotationProperties($properties);
    }

    /**
     * Process the properties.
     *
     * @param array $properties The properties
     *
     * @return array
     */
    protected function processAnnotationProperties(array $properties): array
    {
        // If the type and description exist by the variable does not
        // then that means the variable regex group captured the
        // first word of the description
        if (
            $properties['type']
            && $properties['description']
            && ! $properties['variable']
        ) {
            // Rectify this by concatenating the type and description
            $properties['description'] =
                $properties['type'] . $properties['description'];

            // Then unset the type
            unset($properties['type']);
        }

        // Iterate through the properties
        foreach ($properties as &$property) {
            // Clean each one
            $property = $this->cleanMatch($property);
        }

        return $properties;
    }

    /**
     * Filter a string of arguments into an key => value array.
     *
     * @param string $arguments The arguments
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidAnnotationKeyArgument
     *
     * @return array
     */
    public function getArguments(string $arguments = null): ? array
    {
        $argumentsList = null;

        // If a valid arguments list was passed in
        if (null !== $arguments && $arguments) {
            $testArgs      = str_replace('=', ':', $arguments);
            $argumentsList = json_decode('{' . $testArgs . '}', true);

            if (\is_array($argumentsList)) {
                foreach ($argumentsList as &$value) {
                    $value = $this->determineValue($value);
                }

                unset($value);
            }

            return $argumentsList;
        }

        return $argumentsList;
    }

    /**
     * Determine if a value is a defined constant.
     *
     * @param mixed $value The value to check
     *
     * @return mixed
     */
    protected function determineValue($value)
    {
        if (\is_array($value)) {
            foreach ($value as &$item) {
                $item = $this->determineValue($item);
            }

            unset($item);

            return $value;
        }

        // Trim the value of spaces
        $value = trim($value);

        // If there was no double colon found there's no need to go further
        if (strpos($value, '::') === false) {
            return $value;
        }

        // Determine if the value is a constant
        if (\defined($value)) {
            // Set the value as the constant's value
            return \constant($value);
        }

        [$class, $member] = explode('::', $value);

        // Check for static property
        if (property_exists($class, $member)) {
            return $class::$$member;
        }

        // Check for static method
        if (method_exists($class, $member)) {
            return $class::$member();
        }

        return $value;
    }

    /**
     * Get the annotations regex.
     *
     * @return string
     */
    public function getRegex(): string
    {
        /*
         * @description
         *
         * This regex will produce an array matches list of
         * $matches[0] All matches
         * $matches[1] Annotation name
         *
         * @Annotation($data)
         *
         * $matches[2] Parenthesis enclosed results
         *
         * @Annotation $matches[3] $matches[4] $matches[5]
         * Matches: @method, @param $test, @description description,
         *          @param int $test Description, etc
         * None of the below matches requires the previous one to exist
         *
         * $matches[3] Description or type part of a line annotation
         * $matches[4] Variable part of line annotation
         *             (must be a variable beginning with $)
         * $matches[5] Description part of line annotation
         */
        return '/'
            . self::ANNOTATION_SYMBOL
            . '([a-zA-Z]*)'
            . '(?:' . self::CLASS_REGEX . ')?'
            . self::LINE_REGEX
            . '/x';
    }

    /**
     * Get the arguments regex.
     *
     * @return string
     */
    public function getArgumentsRegex(): string
    {
        return '/' . static::ARGUMENTS_REGEX . '/x';
    }

    /**
     * Get the annotations map.
     *
     * @return array
     */
    public function getAnnotationsMap(): array
    {
        return $this->app->config()['annotations']['map'];
    }

    /**
     * Get an annotation model from the annotations map.
     *
     * @param string $annotationType The annotation type
     *
     * @return \Valkyrja\Annotations\Annotation
     */
    public function getAnnotationFromMap(string $annotationType): Annotation
    {
        // Get the annotations map (annotation name to annotation class)
        $annotationsMap = $this->getAnnotationsMap();

        // If an annotation is mapped to a class
        if ($annotationType && array_key_exists(
                $annotationType,
                $annotationsMap
            )
        ) {
            // Set a new class based on the match found
            $annotation = new $annotationsMap[$annotationType]();
        } else {
            // Otherwise set a new base annotation model
            $annotation = new Annotation();
        }

        return $annotation;
    }

    /**
     * Clean a match from asterisks and new lines.
     *
     * @param string $match The match
     *
     * @return string
     */
    protected function cleanMatch(string $match = null): ? string
    {
        if (! $match) {
            return $match;
        }

        return trim(str_replace('*', '', $match));
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            AnnotationsParser::class,
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
            AnnotationsParser::class,
            new static(
                $app
            )
        );
    }
}
