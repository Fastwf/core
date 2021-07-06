<?php

namespace Fastwf\Core\Router;

use Fastwf\Core\Utils\UuidUtil;
use Fastwf\Core\Router\Exception\ParameterTypeException;
use Fastwf\Core\Router\Exception\ParameterConversionException;

/**
 * Segment specification of declared route.
 */
class Segment {

    private $segment;

    private $isParameter = false;

    private $name = null;
    /**
     * Types:
     *  - int
     *  - float
     *  - string
     *  - uuid
     *
     * @var string
     */
    private $type;

    private $parameter;

    public function __construct($segment) {
        $this->segment = $segment;

        // Analyse the segment to determine if it's a simple string to match or a parameter.
        if ($this->segment[0] === "{" && $this->segment[-1] === "}") {
            // is a parameter to control when match
            $this->isParameter = true;

            $this->prepareParameter();
        }
    }

    // Private methods

    /**
     * Call this method to prepare the segment as parameter segment.
     *
     * The expected parameter segment syntaxt is '{type:parameter_name}'.
     */
    private function prepareParameter() {
        // Search for parameter type
        //  Start at 1 to skip the '{'
        $index = 1;
        $length = strlen($this->segment);

        $accumulator = '';
        while ($index < $length && ($char = $this->segment[$index]) !== '}') {
            if ($char === ':') {
                $this->type = $accumulator;

                $accumulator = '';
            } else {
                $accumulator .= $char;
            }

            $index++;
        }

        $this->name = $accumulator;
        // By default the type is string
        if (!$this->type) {
            $this->type = 'string';
        }
    }

    /**
     * When the segment is a parameter, control the type and convert it before returning if the segment match the parameter specification.
     *
     * @param string $segment
     * @return bool true when the parameter match the segment.
     */
    private function matchParameter($segment) {
        // Control the type and the format when it's is a string
        try {
            switch ($this->type) {
                case 'int':
                    $this->parameter = self::toInt($segment);
                    break;
                case 'float':
                    $this->parameter = self::toFloat($segment);
                    break;
                case 'string':
                    $this->parameter = $segment;
                    // TODO: test the string format
                    break;
                case 'uuid':
                    if (UuidUtil::isUuid($segment)) {
                        $this->parameter = $segment;
                    } else {
                        return false;
                    }
                    break;
                default:
                    throw new ParameterTypeException("Invalid route parameter type '{$this->type}'");
            }
            return true;
        } catch (ParameterConversionException $e) {
            return false;
        }
    }

    // Public methods

    /**
     * Verify that the segment parameter match the current segment definition.
     *
     * @param string $segment
     * @return bool true when the segment match
     */
    public function match($segment) {
        if ($this->isParameter) {
            return $this->matchParameter($segment);
        } else {
            return $this->segment === $segment;
        }
    }

    /**
     * Get the parameter name when the segment is a parameter else null;
     *
     * @return string|null the parameter name or null
     */
    public function getName() {
        return $this->name;
    }

    public function isParameter() {
        return $this->isParameter;
    }

    /**
     * Return the parameter extracted from tested segment.
     *
     * @return int|float|string|null the parameter extracted else null
     */
    public function getParameter() {
        return $this->parameter;
    }

    // Static methods

    /**
     * Try to convert the sequence to int
     *
     * @param string $sequence
     * @return int the sequence converted as int
     * @throws ParameterConversionException when the sequence is not an int
     */
    private static function toInt($sequence) {
        if (is_numeric($sequence) && ($int = intval($sequence)) == $sequence) {
            return $int;
        } else {
            throw new ParameterConversionException("Invalid int '$sequence'");
        }
    }

    /**
     * Try to convert the sequence to float
     *
     * @param string $sequence
     * @return int the sequence converted as float
     * @throws ParameterConversionException when the sequence is not a float
     */
    private static function toFloat($sequence) {
        if (is_numeric($sequence) && ($float = floatval($sequence)) == $sequence) {
            return $float;
        } else {
            throw new ParameterConversionException("Invalid float '$sequence'");
        }
    }

}
