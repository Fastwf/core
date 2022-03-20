<?php

namespace Fastwf\Core;

use Fastwf\Api\Utils\ArrayProxy;
use Fastwf\Api\Exceptions\ValueError;
use Fastwf\Api\Exceptions\IOException;

/**
 * Configuration class that allows to load application configuration from ini file.
 * 
 * The configuration class help to read ini configuration file by respecting the following loading logic:
 *  - Load the given ini file path else throw IOException
 *  - Load the ini file path suffixed with '.local' (before extension) to override the initial configuration
 *  - Finally, environment variable override all loaded info (environment variables not matching a loaded
 *    property is not injected).
 *
 * Because the ini file is loaded without processing sections, a best practice is to prefix the property with
 * the section name ("section.property_name").
 * This rule allows to define environment variable like "SECTION_PROPERTY_NAME". So the only way to override the
 * property is to name the environment variable by respecting the next conversion rule :
 *  - '.' is replaced by '_'
 *  - upercase the result
 *
 * The following example allow to load the next configuration:
 *
 * _config.ini_
 * ```ini
 * [app]
 * app.version = 7.4
 * app.dns = localhost
 * app.port = 8000
 * ```
 * *config.local.ini*
 * ```ini
 * [app]
 * app.port = 9000
 * ```
 *
 * _$ env_
 * ```text
 * APP_DNS = application.com
 * APP_IGNORED = yes
 * ```
 *
 * _loaded configuration_
 * ```php
 * [
 *     'app.version' => 7.4,
 *     'app.dns' => 'application.com',
 *     'app.port' => 9000
 * ]
 * ```
 *
 * Types are automatically parsed, so environment variable are parsed to match the configuration file type.
 */
class Configuration extends ArrayProxy {

    public function __construct($path) {
        parent::__construct();

        if (!\file_exists($path)) {
            throw new IOException("No such file '$path'");
        } else {
            // Load the configuration as a .ini format
            $this->array = $this->loadIniFile($path);

            // Override from .local alternative
            $this->overrideFromLocalAlternative($path);

            // Override using environment variables
            $this->processEnvironmentVariable();
        }
    }

    /// Private methods

    /**
     * Averride the configuration file using the base config file path.
     *
     * @param string $path the path to the original configuration file.
     */
    private function overrideFromLocalAlternative($path) {
        $info = \pathinfo($path);

        // Build the local alternative path
        $alternativePath = \join(
            DIRECTORY_SEPARATOR,
            [$info['dirname'], $info['filename'] . ".local." . $info['extension']]
        );

        if (\file_exists($alternativePath)) {
            // Update the array from the local alternative
            $this->update($this->loadIniFile($alternativePath));
        }
    }

    /**
     * Override configuration properties using the corresponding environment variable.
     */
    private function processEnvironmentVariable() {
        // Iterate over all configuration properties
        foreach ($this->array as $name => $value) {
            $environmentName = \strtoupper(\str_replace(".", "_", $name));

            if (\array_key_exists($environmentName, $_ENV)) {
                $var = null;
                switch (\gettype($value)) {
                    case 'integer':
                        $var = \intval($_ENV[$environmentName]);
                        break;
                    case 'double':
                        $var = \floatval($_ENV[$environmentName]);
                        break;
                    case 'boolean':
                        $var = self::parseBoolean($_ENV[$environmentName]);
                        break;
                    case 'string':
                        $var = $_ENV[$environmentName];
                        break;
                    default:
                        // the variable cannot be processed from environment
                        break;
                }

                if ($var !== null) {
                    $this->array[$name] = $var;
                }
            }
        }
    }

    // Protected methods

    /**
     * Load the ini file as expected ini rules.
     *
     * @param string $path the path to the ini file to parse
     * @return array the array loaded
     */
    protected function loadIniFile($path) {
        return \parse_ini_file($path, false, INI_SCANNER_TYPED);
    }

    /**
     * Try to parse a boolean by respecting the ini file format:
     *  - 'true', 'on', 'yes', '1' -> true
     *  - 'false', 'off', 'no', '0' -> false
     *
     * @param string $seq the sequence to parse
     * @return bool the value parsed
     */
    public static function parseBoolean($seq) {
        $seq = \strtolower($seq);

        if (\in_array($seq, ["true", "on", "yes", "1"])) {
            return true;
        } else if (\in_array($seq, ["false", "off", "no", "0"])) {
            return false;
        } else {
            // Invalid boolean value
            throw new ValueError("'$seq' cannot be converted as boolean");
        }
    }

}
