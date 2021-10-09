<?php

namespace Fastwf\Core\Router\Formatter;

use Fastwf\Core\Router\BaseRoute;
use Fastwf\Core\Utils\StringUtil;
use Fastwf\Core\Exceptions\KeyError;
use Fastwf\Core\Router\Exception\FormatException;
use Fastwf\Core\Router\Parser\SpecificationRouteParser;

/**
 * Helper class to use to format part of the path.
 * 
 * This class must be used to wrap a BaseRoute to format it using it's path and name.
 * 
 * Warning: when a partial path contains path segment type, it will be the last element formatted.
 */
class PartialPathFormatter
{

    private $name;
    private $path;

    /**
     * Constructor
     *
     * @param \Fastwf\Core\Router\Formatter\IBaseRoute $baseRoute
     */
    public function __construct($baseRoute)
    {
        $this->name = $baseRoute->getName();
        $this->path = $baseRoute->getPath();
    }

    /**
     * Extract the sequence according to the segment specification.
     * 
     * Use null $parameters only when the segment must be debugged.
     *
     * @param Fastwf\Core\Router\Segment $segment
     * @param array|null $parameters the array of parameters to inject or null to prevent injection
     * @param bool $isPath the out variable that is set to true when the segment is a path parameter
     * @return string the url encoded segment
     */
    private function formatSegment($segment, $parameters, &$isPath)
    {
        if ($segment->isWildcard())
        {
            throw new FormatException("Wildcard segment cannot be formatted");
        }
        else if ($parameters !== null && $segment->isParameter())
        {
            $sequence = $this->formatSegmentParameter($segment, $parameters, $isPath);
        }
        else
        {
            $sequence = $segment->getSegment();

            // When parameters is not null the segment must be encoded
            if ($parameters !== null)
            {
                $sequence = \urlencode($sequence);
            }
        }

        return $sequence;
    }

    /**
     * Format the segment like a parameter segment.
     *
     * @param Fastwf\Core\Router\Segment $segment the segment specification
     * @param array $parameters the parameter that can be injected
     * @param bool $isPath the out variable that is set to true when the segment is a path parameter
     * @return string the segment parameter formatted with paramter injection
     */
    private function formatSegmentParameter($segment, $parameters, &$isPath)
    {
        $parameterName = BaseRoute::getParameterName($this->name, $segment->getName());

        // Verify that the parameter is provided else throw KeyError
        if (!\array_key_exists($parameterName, $parameters))
        {
            throw new KeyError("Parameter name '$parameterName' was not found in the provided parameter array.");
        }

        $parameter = $parameters[$parameterName];

        // Convert the parameter to string
        $sequence = \is_string($parameter) ? $parameter : \strval($parameter);

        // Complete by encoding the segment to respecting urlencoding rules
        if ($segment->isPath())
        {
            $isPath = true;
            $sequence = self::pathUrlEncode($sequence);
        }
        else
        {
            $sequence = \urlencode($sequence);
        }

        return $sequence;
    }

    /**
     * Return the path with segment formatted using the parameters array when it's required.
     *
     * @param array $parameters the parameters to apply to the path
     * @return string the formatted path
     */
    public function format($parameters) {
        // Use SpecificationRouteParser to iterate over segment and apply formatting rules according to the segment context
        $parser = new SpecificationRouteParser($this->path);
        $parser->rewind();

        // Do not use foreach to prevent to inject '/' to the first segment
        if ($parser->valid()) {
            $isPath = false;
            $formattedPath = $this->formatSegment($parser->current(), $parameters, $isPath);

            $parser->next();
            while ($parser->valid() && !$isPath) {
                // Glue the segment value to the formatted path
                if (!StringUtil::endsWith($formattedPath, '/')) {
                    $formattedPath .= '/';
                }
                $formattedPath .= $this->formatSegment($parser->current(), $parameters, $isPath);

                $parser->next();
            }
        } else {
            // Set the path when there is no segments
            $formattedPath = '';
        }

        return $formattedPath;
    }

    /**
     * Url encode each segment of the path.
     * 
     * @param string $path the path to encode
     * @return string the path urlencoded preserving '/'.
     */
    public static function pathUrlEncode($path)
    {
        return \implode(
            '/',
            \array_map(
                "\urlencode",
                explode('/', $path),
            ),
        );
    }

}
