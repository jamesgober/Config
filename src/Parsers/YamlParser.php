<?php
/**
 * YamlParser.php
 * 
 * Parses configuration data from YAML files and converts it into an associative array.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Parsers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Parsers;

use \is_file;
use \sprintf;
use \is_readable;
use Symfony\Component\Yaml\Yaml;
use \JG\Config\Exceptions\ConfigParseException;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * YamlParser
 * 
 * Parses YAML configuration files, converting their content into associative arrays.
 * Handles invalid YAML gracefully and provides detailed error messages.
 * 
 * @package JG\Config\Parsers
 * @uses    Symfony\Component\Yaml\Yaml
 * @uses    Symfony\Component\Yaml\Exception\ParseException
 */
class YamlParser implements ParserInterface
{
    /**
     * Parses a YAML configuration file and returns its contents as an associative array.
     *
     * The method validates the file's existence and readability before attempting to parse.
     *
     * Example YAML file:
     * ```
     * database:
     *     host: localhost
     *     port: 3306
     *     user: root
     * app:
     *     debug: true
     *     cache: null
     * ```
     *
     * @param string $filePath The path to the YAML file.
     * @return array The parsed contents of the YAML file as an associative array.
     * @throws ConfigParseException If the YAML file cannot be read or contains invalid YAML.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException(
                sprintf("YAML file not found or unreadable: %s", $filePath)
            );
        }

        try {
            $data = Yaml::parseFile($filePath);
        } catch (ParseException $e) {
            throw new ConfigParseException(
                sprintf("Error parsing YAML file '%s': %s", $filePath, $e->getMessage())
            );
        }

        return $data ?? [];
    }
}