<?php

namespace Fastwf\Core\Router\Formatter;

use Fastwf\Core\Utils\StringUtil;

/**
 * Helper class that format an absolute url path.
 */
class PathFormatter
{

    private $paths;

    public function __construct($partialPathFormatters)
    {
        $this->paths = $partialPathFormatters;
    }

    /**
     * Format the path using parameters.
     *
     * @param array|null $parameters the parameters to inject in the path
     * @return string the path formatted.
     */
    public function format($parameters = null)
    {
        $fullPath = '';
        foreach ($this->paths as $partialPathFormatter) {
            $path = $partialPathFormatter->format($parameters);

            // Prevent merging empty segment (case of mount point on '' path)
            if ($path !== '')
            {
                // Inject the  '/' when it's required between the 
                if (!StringUtil::endsWith($fullPath, '/'))
                {
                    $fullPath .= '/';
                }

                $fullPath .= $path;   
            }
        }

        return $fullPath === '' ? '/' : $fullPath;
    }

}
