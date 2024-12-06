<h1 align="center">
    <picture picture>
        <source media="(prefers-color-scheme: dark)" srcset="./docs/media/jamesgober-logo-dark.png">
        <img width="72" height="72" alt="Official brand mark and logo of James Gober. Image shows JG stylish initials encased in a hexagon outline." src="./docs/media/jamesgober-logo.png">
    </picture>
    <br>
    <b>CONFIG MANAGER</b>
    <br>
    <sup>BY JAMES GOBER</sup>
    <br>
</h1>

<div align="center">
    <img src="https://img.shields.io/github/stars/jamesgober/config?style=flat" alt="GitHub Stars"> &nbsp; 
    <img src="https://img.shields.io/github/issues/jamesgober/config?style=flat" alt="GitHub Issues"> &nbsp;  
    <img src="https://img.shields.io/github/v/release/jamesgober/config?display_name=tag&style=flat" alt="GitHub Release"> &nbsp; 
    <img src="https://img.shields.io/github/license/jamesgober/config?style=flat" alt="GitHub License"> &nbsp;
    <img src="https://img.shields.io/badge/PHP-8.2-blue?style=flat" alt="PHP Version">
    <a href="https://packagist.org/packages/jamesgober/config" target="_blank">
        <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/jamesgober/config?style=flat&color=%23f26f1a">
    </a>
</div>

&nbsp;

<h2>A Flexible PHP Configuration Manager</h2>

Config is a lightweight yet powerful PHP library designed to handle your application's configuration needs with ease and efficiency. Supporting multiple formats like **JSON**, **XML**, **YAML**, **INI**, and **PHP** arrays out of the box, it provides the flexibility to add custom parsers tailored to your specific requirements. Config offers a modular design, efficient caching capabilities, and a straightforward API to streamline configuration management in modern PHP projects.


&nbsp;


## Key Features

- **Multi-Format Support**: Seamlessly load configurations from **JSON**, **XML**, **YAML**, **INI**, and **PHP** array files.
- **Custom Parsers**: Extend functionality by adding support for any file format with ease.
- **Organized Management**: Efficiently manage configuration data with a modular and extendable design.
- **Robust Error Handling**: Custom exceptions provide clear and actionable feedback for debugging.
- **Lightweight and Intuitive**: A simple yet powerful API designed for minimal overhead and ease of use.


&nbsp;



## Why Choose This Library?

When it comes to configuration management, JG\Config stands out for its:

- **Multi-Format Support**: Handles **JSON**, **YAML**, **INI**, **XML**, **PHP** arrays, and **CONF** files with ease.
- **Modular Design**: Extend or replace parsers to meet your specific needs.
- **Performance Optimizations**: Engineered for speed, even with large or deeply nested configurations.
- **Advanced Features**: Offers caching, dynamic flattening control, and stream parsing for modern PHP projects.
- **Error-Resilient**: Comprehensive error handling and detailed exception messages for seamless debugging.
- **Tested Reliability**: Rigorously tested with PHPUnit to ensure predictable and stable performance.

Whether you’re building a small application or managing a complex project, JG\Config provides the tools to handle your configuration needs efficiently.

&nbsp;


&nbsp;


## Installation

Install via [composer](https://getcomposer.org/download/):

```bash
$ composer require jamesgober/config
```
&nbsp;

&nbsp;

## Quick Start


#### Load Configurations
Effortlessly load multiple configuration files in various formats:
```php
use JG\Config\Config;

$config = new Config('/path/to/config/files');

// Load files
$config->load('database.json');
$config->load('app.xml');

// Access values
$dbHost = $config->get('database.host', 'default_host');

// Check keys
if ($config->has('app.debug')) {
    echo "Debug mode is enabled!";
}

// Add or update values
$config->add('app.name', 'MyApp');
```

&nbsp;

#### Manage Configurations
Easily delete individual values or entire groups:
```php
// Delete specific values
$config->delete('app.version'); // Removes app.version

// Delete groups
$config->delete('app'); // Removes app.name, app.debug, etc.
```

&nbsp;

#### Extend with Custom Parsers
Add support for new file formats via custom parsers:
```php
use JG\Config\ConfigParserFactory;

// Register a parser for .custom files
ConfigParserFactory::registerParser('custom', MyCustomParser::class);
```

---

&nbsp;

### **Supported Formats**

| **Format** | **Example**       | **Extension** |
|------------|-------------------|----------------|
| CONF       | `config.conf`     | `.conf`        |
| INI        | `config.ini`      | `.ini`         |
| JSON       | `config.json`     | `.json`        |
| PHP Array  | `config.php`      | `.php`         |
| XML        | `config.xml`      | `.xml`         |
| YAML       | `config.yaml`     | `.yaml`, `.yml`|


&nbsp;

#### Example Configurations

**PHP Array (`config.php`)**
```php
return [
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'password' => 'secret',
];
```

**JSON (`config.json`)**
```json
{
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "secret"
}
```

&nbsp;

**INI (`config.ini`)**
```ini
[app]
name    = "TestApp"
version = "1.0.0"
debug   = true
cache   = null
```

&nbsp;

**XML (`config.xml`)**
```xml
<app>
    <name>TestApp</name>
    <version>1.0.0</version>
    <debug>true</debug>
    <cache>null</cache>
</app>
```

&nbsp;

**YAML (`config.yaml`)**
```yaml
database:
    host: localhost
    port: 3306
    user: root
    password: secret

app:
    debug: true
    cache: null
```

---

&nbsp;

### **Error Handling**


#### Custom Exceptions

JG\Config uses custom exceptions for intuitive error handling:

| **Exception**                           | **Description**                                |
|-----------------------------------------|------------------------------------------------|
| `ConfigException`                       | General errors related to configuration.       |
| `ConfigParseException`                  | Errors when parsing configuration files.       |
| `InvalidParserException`                | When unsupported or invalid parsers are used.  |


&nbsp;

**Example Usage:**
```php
use JG\Config\Exceptions\ConfigException;

try {
    $config->load('missing_file.json');
} catch (ConfigException $e) {
    echo "Error: " . $e->getMessage();
}
```

&nbsp;

## Advanced Features

Unlock additional capabilities with these advanced features designed for power users:

&nbsp;

### 1. Custom Parsers
Extend the library to support additional file formats by registering custom parsers:
```php
use JG\Config\ConfigParserFactory;

// Register a custom parser for `.custom` files
ConfigParserFactory::registerParser('custom', MyCustomParser::class);
```
Your custom parser must implement the `ParserInterface`:
```php
use JG\Config\Parsers\ParserInterface;

class MyCustomParser implements ParserInterface {
    public function parse(string $filePath): array {
        // Custom parsing logic
    }
}
```

&nbsp;

### 2. Dynamic Flattening Control
Toggle the flattening of nested configuration keys dynamically:
```php
$config = new Config('/path/to/config', false); // Disable flattening
$config->load('nested_config.json');

// Access without flattening
$nested = $config->get('database.credentials');
```

&nbsp;

### 3. Advanced Caching
Leverage robust caching mechanisms for optimal performance:
- Save configurations with expiration:
  ```php
  $config->saveCache('/path/to/cache.json', Config::EXPIRE_ONE_DAY);
  ```
- Load cached configurations:
  ```php
  if ($config->loadCache('/path/to/cache.json')) {
      echo "Loaded from cache!";
  }
  ```
- Handle cache expiration and invalidation seamlessly:
  ```php
  $config->deleteCache('/path/to/cache.json');
  ```

&nbsp;

### 4. Stream Parsing
Load configurations from streams (e.g., PSR-7 compatible):
```php
use Psr\Http\Message\StreamInterface;

$config->loadFromStream($stream);
```

&nbsp;

### 5. Error and Debugging Utilities
Identify and resolve configuration issues with informative error messages:
- Handle invalid file formats, parsing errors, and more with custom exceptions.
- Access meaningful error details:
  ```php
  try {
      $config->load('invalid_file.xyz');
  } catch (ConfigException $e) {
      echo $e->getMessage();
  }
  ```


&nbsp;


## Directory Structure

The library is organized to ensure scalability, maintainability, and ease of contribution. Here's an overview:

```
src/
├── Config.php                  # Core configuration management class
├── ConfigParserFactory.php     # Factory for parser resolution
├── Exceptions/                 # Custom exceptions for robust error handling
│   ├── ConfigException.php
│   ├── ConfigParseException.php
│   └── InvalidParserException.php
├── Parsers/                    # Built-in parsers for supported formats
│   ├── ParserInterface.php
│   ├── PhpParser.php
│   ├── JsonParser.php
│   ├── IniParser.php
│   ├── XmlParser.php
│   └── YamlParser.php
tests/                          # Unit and integration tests


```

&nbsp;

The library follows a modular structure. Here's what each part does:

- `src/`: Contains the core library code.
  - `Config.php`: The main configuration management class.
  - `ConfigParserFactory.php`: Dynamically resolves the appropriate parser for a given file format.
  - `Exceptions/`: Custom exceptions for handling errors gracefully.
  - `Parsers/`: Houses all built-in parsers (e.g., JSON, INI, XML, YAML).
- `tests/`: PHPUnit tests ensuring stability and reliability.

&nbsp;



---

&nbsp;


## Benchmarks
&#9888; Coming soon.
<!-- Coming soon: Detailed performance benchmarks across various configuration formats and scenarios. -->

&nbsp;

## Roadmap or Future Goals
&#9888; Coming soon.
<!-- Coming Soon -->

&nbsp;

## Contributing

We welcome contributions! Please submit pull requests or report issues on [GitHub](https://github.com/jamesgober/Config).

&nbsp;


## **Changelog**
For a detailed list of changes and updates in each version, please refer to the [CHANGELOG](CHANGELOG.md).


&nbsp;

## License

This library is open-source software licensed under the [MIT license](LICENSE).


&nbsp;


&nbsp;

<div align="center">
    <a href="#top">&uarr;<br>TOP</a>
</div>

<h2></h2>
<p align="center"><small>COPYRIGHT &copy; 2024 JAMES GOBER.</small></p>

&nbsp;
