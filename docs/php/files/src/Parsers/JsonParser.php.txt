<?php
/**
 * JsonParser.php
 * 
 * Parses configuration data from JSON files. The file must contain valid JSON
 * that can be converted into an associative array.
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
use \json_decode;
use \JSON_ERROR_NONE;
use \json_last_error;
use \file_get_contents;
use \json_last_error_msg;
use \JG\Config\Exceptions\ConfigParseException;

/**
 * JsonParser
 * 
 * Parses configuration data from JSON files. This parser reads the JSON file,
 * decodes its contents into an associative array, and validates the structure.
 * 
 * @package JG\Config\Parsers
 */
class JsonParser implements ParserInterface
{
    /**
     * Parses a JSON configuration file and returns its contents as an associative array.
     *
     * The method validates that the file exists, is readable, and contains valid JSON.
     *
     * Example JSON file:
     * ```
     * {
     *     "database": {
     *         "host": "localhost",
     *         "port": 3306
     *     },
     *     "app": {
     *         "debug": true
     *     }
     * }
     * ```
     *
     * @param string $filePath Path to the JSON configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigParseException If the file cannot be read or contains invalid JSON.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException(
                sprintf("JSON file not found or unreadable: %s", $filePath)
            );
        }

        $json = file_get_contents($filePath);
        if ($json === false) {
            throw new ConfigParseException(
                sprintf("Failed to read JSON file: %s", $filePath)
            );
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigParseException(
                sprintf("Invalid JSON in file: %s. Error: %s", $filePath, json_last_error_msg())
            );
        }

        return $data ?? [];
    }
}