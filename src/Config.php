<?php
/**
 * Config.php
 * 
 * The core configuration management class.
 * Provides methods to load, parse, and manage configuration data from 
 * various sources, including JSON, YAML, XML, and more.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config
 * @version    1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config;

use \ltrim;
use \rtrim;
use \is_dir;
use \strtok;
use \is_file;
use \strtolower;
use \array_merge;
use \preg_replace;
use \JG\Config\ConfigParserFactory;
use \JG\Config\Exceptions\ConfigException;

/**
 * Configuration Manager
 * 
 * Manages the loading, parsing, and handling of configuration files.
 * Supports multiple formats such as JSON, YAML, and XML, with the ability
 * to extend via custom parsers.
 * 
 * @package JG\Config
 */
class Config
{
    /**
     * Default configuration directory path.
     *
     * @var string|null
     */
    private ?string $configPath = null;

    /**
     * Whether configuration keys should be flattened.
     *
     * @var bool
     */
    private bool $flatten = true;

    /**
     * Grouped configuration storage.
     *
     * Stores configurations by groups, allowing access by original
     * source filename or unique key.
     *
     * @var array<string, array<string, string>>
     */
    private array $groups = [];

    /**
     * Primary configuration storage.
     *
     * Stores all configuration key-value pairs, including unique keys
     * generated from grouped configurations.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    /**
     * Constructor.
     *
     * @param string|null $configPath Default path for config files (optional).
     * @param bool $flattenConfig Whether to enable key flattening (default: true).
     */
    public function __construct(?string $configPath = null, bool $flattenConfig = true)
    {
        $this->setConfigPath($configPath);
        $this->setFlatten($flattenConfig);
    }

    /**
     * Sets the configuration directory path.
     *
     * @param string|null $path Absolute or relative path to the config directory.
     * @return void
     * 
     * @throws ConfigException If the path is invalid.
     */
    public function setConfigPath(?string $path = null): void
    {
        if ($path && !is_dir($path)) {
            throw new ConfigException("Invalid configuration directory: {$path}");
        }
        $this->configPath = $path ? rtrim($path, '/') . '/' : null;
    }

    /**
     * Enables or disables key flattening.
     *
     * @param bool $flatten Whether to enable flattening.
     * @return void
     */
    public function setFlatten(bool $flatten): void
    {
        $this->flatten = $flatten;
    }

    /**
     * Loads configuration data from a file.
     *
     * Reads the specified file, parses its contents, and merges 
     * the data into the existing configuration. Optionally, groups
     * configuration keys by the file's base name to create unique keys.
     *
     * @param string|null $filePath   Absolute or relative path to the file.
     * @param bool        $makeUnique Whether to group keys by file base name.
     * 
     * @return bool  True if the configuration was successfully loaded.
     */
    public function load(?string $filePath = null, bool $makeUnique = true): bool
    {
    
        $data = $parser->parse($filePath);
        if (!is_array($data)) {
            return false;
        }

        return $makeUnique ? $this->groupData($data, $filePath) : $this->merge($data);
    }

    /**
     * Resolves the full file path.
     *
     * @param string|null $filePath The file path or filename (optional).
     * @return string|null The resolved file path, or null if unresolved.
     */
    private function resolvePath(?string $filePath): ?string
    {
        if (!$filePath) {
            return null;
        }

        if (is_file($filePath)) {
            return $filePath;
        }

        return $this->configPath ? $this->configPath . ltrim($filePath, '/') : null;
    }

    /**
     * Inserts configuration data into the manager.
     *
     * @param array<string, mixed> $config Configuration key-value pairs.
     * @param array<string, array<string, string>> $groups Configuration groups.
     * @return void
     */
    public function insert(array $config = [], array $groups = []): void
    {
        $this->groups = array_merge($this->groups, $groups);
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Parses a configuration file.
     *
     * @param string|null $filePath Absolute or relative path to the file.
     * @return array<string, mixed>|null Parsed configuration data, or null if parsing fails.
     * 
     * @throws ConfigException If the file does not exist or cannot be parsed.
     */
    protected function parse(?string $filePath = null): ?array
    {
        $filePath = $this->resolvePath($filePath);

        if (!$filePath || !is_file($filePath)) {
            throw new ConfigException("Configuration file not found: {$filePath}");
        }

        $parser = ConfigParserFactory::createParser($filePath);
        if (!$parser) {
            throw new ConfigException("No suitable parser found for: {$filePath}");
        }

        return $parser->parse($filePath);
    }

    /**
     * Retrieves configuration from a file.
     *
     * @param string|null $filePath Path to the configuration file.
     * @return array<string, mixed> The parsed configuration data.
     */
    public function fetch(?string $filePath = null): array
    {
        return $this->parse($filePath) ?? [];
    }

    /**
     * Checks if a specific configuration key exists.
     *
     * @param string $key The configuration key to check.
     * @return bool True if the key exists; otherwise, false.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Adds or updates a configuration value.
     *
     * @param string $key The configuration key.
     * @param mixed $value The configuration value.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Retrieves a configuration value.
     *
     * @param string $key The configuration key.
     * @param mixed $default Default value if the key is not found.
     * @return mixed|null The configuration value, or the default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Deletes a configuration key or group.
     *
     * @param string $key The key or group to delete.
     * @return void
     */
    public function delete(string $key): void
    {
        unset($this->config[$key], $this->groups[$key]);
    }

    /**
     * Clears all configuration data.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->config = [];
        $this->groups = [];
    }
}