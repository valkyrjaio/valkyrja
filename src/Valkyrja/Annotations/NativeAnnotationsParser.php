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

use Valkyrja\Annotations\Exceptions\InvalidAnnotationKeyArgument;
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
            // Set an arguments list to return
            $argumentsList = [];

            // Get all arguments from the arguments string
            /** @var array[] $matches */
            $matches = $this->getArgumentMatches($arguments);

            // Iterate through the matches
            foreach ($matches[0] as $index => $match) {
                $this->setArgument($matches, $index, $argumentsList);
            }

            return $argumentsList;
        }

        return $argumentsList;
    }

    /**
     * Get the argument matches.
     *
     * @param string $arguments The arguments
     *
     * @return array
     */
    protected function getArgumentMatches(string $arguments): ? array
    {
        preg_match_all($this->getArgumentsRegex(), $arguments, $matches);

        return $matches ?? null;
    }

    /**
     * Set a matched argument.
     *
     * @description The matches [0 => $matches, 1 => $keys, 2 => $values]
     *
     * @param array $matches   The matches [0 => $matches, 1 => $keys, 2 =>
     *                         $values]
     * @param int   $index     The index
     * @param array $arguments The arguments list
     *
     * @throws \Valkyrja\Annotations\Exceptions\InvalidAnnotationKeyArgument
     *
     * @return void
     */
    protected function setArgument(array $matches, int $index, array &$arguments): void
    {
        // Set the key
        $key = $this->determineValue($this->cleanMatch($matches[1][$index]));
        // Set the value
        $value = $this->determineValue($this->cleanMatch($matches[2][$index]));

        // Constants can be bool, int, string, or arrays
        // If the key is an array throw an exception
        if (! \is_int($key) && ! \is_string($key) && ! \is_bool($key)) {
            throw new InvalidAnnotationKeyArgument('Invalid key specified.');
        }

        // Set the key value pair in the list
        $arguments[$key] = $value;
    }

    /**
     * Determine if a value is a defined constant.
     *
     * @param string $value The value to check
     *
     * @return mixed
     */
    protected function determineValue(string $value)
    {
        // Trim the value of spaces
        $value = trim($value);

        // If this value starts with [[ and has pipe deliminations within it
        // then it's an array of values to parse (ex: [[Test | Test2 | Test3]]
        if (
            strpos($value, '[[') === 0
            && strpos($value, ']]') === \strlen($value) - 2
        ) {
            // Strip the value of the [[ ]] at the ends of the string
            $value = (string) substr($value, 2, -2);
            // Split the value into parts
            $parts = explode(' | ', $value);

            // Iterate through the parts
            foreach ($parts as &$part) {
                // Ensure the part is valid
                if (! $part) {
                    continue;
                }

                // Set the part as a recurse of the part in case special cases were
                // used within the array such as another array, constant, etc
                $part = $this->determineValue($part);
            }

            return $parts;
        }

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
