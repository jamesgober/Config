<?php
/**
 * PhpParser.php
 * 
 * Parses configuration data from PHP files. The file must return an array 
 * representing the configuration, or an exception will be thrown.
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
use \JG\Config\Exceptions\ConfigParseException;

/**
 * PhpParser
 * 
 * Parses configuration data from PHP files. The PHP file should return an 
 * associative array, representing the configuration settings.
 * 
 * @package JG\Config\Parsers
 */
class PhpParser implements ParserInterface
{
    /**
     * Parses a PHP configuration file and returns its contents as an array.
     *
     * The file must return an array. If it doesn't, or if the file cannot be read,
     * an exception is thrown.
     *
     * @param string $filePath Path to the PHP configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigParseException If the file is not readable or does not return an array.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException(
                sprintf("Failed to parse PHP configuration file. File is not readable: %s", $filePath)
            );
        }

        $data = require $filePath;

        if (!is_array($data)) {
            throw new ConfigParseException(
                sprintf("PHP configuration file must return an array. Error in file: %s", $filePath)
            );
        }

        return $data;
    }
}