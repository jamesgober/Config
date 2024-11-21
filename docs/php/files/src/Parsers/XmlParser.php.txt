<?php
/**
 * XmlParser.php
 * 
 * Parses configuration data from XML files. Converts XML structures into 
 * associative arrays for easy manipulation and usage.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Parsers
 * @version    1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Parsers;

use \is_file;
use \is_numeric;
use \is_readable;
use \SimpleXMLElement;
use \libxml_get_errors;
use \libxml_clear_errors;
use \simplexml_load_file;
use \libxml_use_internal_errors;
use \JG\Config\Exceptions\ConfigParseException;

/**
 * XmlParser
 * 
 * Parses configuration data from XML files, converting them into associative arrays.
 * Handles XML errors gracefully and provides meaningful error messages.
 * 
 * @package JG\Config\Parsers
 */
class XmlParser implements ParserInterface
{
    /**
     * Parses an XML configuration file and returns its contents as an associative array.
     *
     * @param string $filePath Path to the XML configuration file.
     * @return array The parsed configuration data.
     * @throws ConfigParseException If the XML file cannot be read or contains invalid XML.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException(
                sprintf("XML file not found or unreadable: %s", $filePath)
            );
        }

        libxml_use_internal_errors(true);

        $data = simplexml_load_file($filePath, SimpleXMLElement::class, LIBXML_NOERROR | LIBXML_NOWARNING);

        if ($data === false) {
            $this->handleXmlErrors($filePath);
        }

        return $this->convertXmlToArray($data);
    }

    /**
     * Handles XML parsing errors by throwing an exception with detailed information.
     *
     * @param string|null $filePath The file path for context in error messages.
     * @return void
     * @throws ConfigParseException Always throws with the XML error details.
     */
    protected function handleXmlErrors(?string $filePath): void
    {
        $errors = libxml_get_errors();
        libxml_clear_errors();

        $latestError = end($errors);
        $errorMessage = $latestError ? $latestError->message : 'Unknown XML parsing error';

        throw new ConfigParseException(
            sprintf(
                "XML parsing error in file '%s': %s on line %d",
                $filePath ?? 'unknown',
                $errorMessage,
                $latestError->line ?? 0
            )
        );
    }

    /**
     * Converts a `SimpleXMLElement` object into an associative array or scalar value.
     *
     * @param SimpleXMLElement $data The XML data to convert.
     * @return array|string|mixed The converted associative array or scalar value.
     */
    protected function convertXmlToArray(SimpleXMLElement $data): mixed
    {
        // Handle empty nodes explicitly
        if (!$data->count() && !$data->attributes()) {
            $value = (string) $data;
            return $value === '' ? null : $this->convertValue($value);
        }
    
        $result = [];
    
        // Convert children recursively
        foreach ($data->children() as $key => $value) {
            $result[$key] = $this->convertXmlToArray($value);
        }
    
        // Include attributes if present
        foreach ($data->attributes() as $attrName => $attrValue) {
            $result['@attributes'][$attrName] = $this->convertValue((string) $attrValue);
        }
    
        return $result;
    }

    /**
     * Converts string values to their respective PHP scalar types where applicable.
     *
     * - `true` and `false` become boolean.
     * - `null` becomes null.
     * - Numeric strings are cast to integers or floats.
     *
     * @param string $value The value to convert.
     * @return mixed Converted value.
     */
    private function convertValue(string $value): mixed
    {
        $lowerValue = strtolower($value);

        return match (true) {
            $lowerValue === 'true'  => true,
            $lowerValue === 'false' => false,
            $lowerValue === 'null'  => null,
            is_numeric($value)      => $value + 0, // Converts to int or float
            default                 => $value,
        };
    }
}