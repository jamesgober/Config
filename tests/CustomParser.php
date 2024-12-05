<?php

namespace JG\Tests;

use JG\Config\Parsers\ParserInterface;
use JG\Config\Exceptions\ConfigParseException;

class CustomParser implements ParserInterface
{
    /**
     * Parses a custom configuration file into an associative array.
     *
     * @param string $filePath Path to the custom configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigParseException If the file cannot be read or parsed.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException("File not found or unreadable: {$filePath}");
        }

        $data = file_get_contents($filePath);
        if ($data === false) {
            throw new ConfigParseException("Failed to read the custom configuration file: {$filePath}");
        }

        $lines = explode(PHP_EOL, $data);
        $output = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || str_starts_with($line, '#')) {
                continue; // Skip empty lines or comments
            }

            if (!str_contains($line, '->')) {
                throw new ConfigParseException("Invalid line format in file {$filePath}: {$line}");
            }

            [$key, $value] = array_map('trim', explode('->', $line, 2));
            $output[$key] = $value;
        }
        return $output;
    }
}