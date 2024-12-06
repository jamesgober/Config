<h1 align="center">
    <picture>
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
    <img src="https://img.shields.io/badge/PHP-8.2-blue?style=flat" alt="PHP Version"> &nbsp;
    <a href="https://packagist.org/packages/jamesgober/config" target="_blank">
        <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/jamesgober/config?style=flat&color=%23f26f1a">
    </a>
</div>

&nbsp;

<h2>A Flexible PHP Configuration Manager</h2>

Config is a lightweight yet powerful PHP library designed to handle your application's configuration needs with ease and efficiency. Supporting multiple formats like **JSON**, **XML**, **YAML**, **INI**, and **PHP** arrays out of the box, it provides the flexibility to add custom parsers tailored to your specific requirements. With robust error handling, performance-focused design, and a modular architecture, **JG\Config** simplifies configuration management for modern PHP projects.

&nbsp;

## Key Features

- **Multi-Format Support**: Seamlessly load configurations from **JSON**, **XML**, **YAML**, **INI**, **PHP**, and **CONF** files.
- **Custom Parsers**: Extend functionality by adding support for any file format with ease.
- **Performance Optimizations**: Designed for speed, even with large or deeply nested configurations.
- **Advanced Features**: Includes caching, dynamic flattening control, and stream parsing for modern workflows.
- **Robust Error Handling**: Custom exceptions provide clear and actionable feedback for debugging.
- **Lightweight and Intuitive**: A simple yet powerful API designed for minimal overhead and ease of use.

&nbsp;

## Why Choose This Library?

**JG\Config** is built for developers who demand efficiency, reliability, and flexibility in their projects:

- **High Performance**: Optimized for speed with low overhead, making it ideal for high-load scenarios.
- **Modular Design**: Extend or replace parsers effortlessly to meet custom needs.
- **Error-Resilient**: Provides comprehensive error handling with meaningful exception messages.
- **Extensively Tested**: Rigorously tested with PHPUnit for predictable and stable performance.
- **Built on Principles**: Adheres to S.O.L.I.D design principles and emphasizes maintainability and extensibility.

Whether you're building a small application or managing a large-scale project, **JG\Config** provides a robust solution to streamline your configuration management.

&nbsp;

---

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

## Supported Formats

| **Format** | **Example**       | **Extension** |
|------------|-------------------|----------------|
| CONF       | `config.conf`     | `.conf`        |
| INI        | `config.ini`      | `.ini`         |
| JSON       | `config.json`     | `.json`        |
| PHP Array  | `config.php`      | `.php`         |
| XML        | `config.xml`      | `.xml`         |
| YAML       | `config.yaml`     | `.yaml`, `.yml`|

&nbsp;

For more examples, see our **[Advanced Usage](docs/ADVANCED.md)** guide.

&nbsp;

---

## Error Handling

### Custom Exceptions

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

---

&nbsp;

## Advanced Features

Explore advanced capabilities like caching, custom parsers, and dynamic flattening in our **[Advanced Guide](docs/ADVANCED.md)**.

&nbsp;

---

## Directory Structure

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

---

&nbsp;

## Reporting Security Issues

We take security seriously. If you find a vulnerability, please consult our **[SECURITY POLICY](.github/SECURITY.md)** and follow the instructions for reporting. 

Do not use public issue trackers or forums to disclose sensitive information.

&nbsp;

---

## Reporting Bugs and Feature Requests

For non-security issues, such as bugs or feature requests, please use our **[Issue Tracker](https://github.com/jamesgober/Config/issues)**. Providing detailed information will help us resolve issues efficiently.

&nbsp;

---

## Design Philosophy

Learn about the principles that guide the development of **JG\Config** in our **[Design Philosophy](docs/DESIGN_PHILOSOPHY.md)**.

&nbsp;

---

## Contributing

Contributions are welcome! Check out the **[Contribution Guidelines](.github/CONTRIBUTING.md)** and review our **[Code of Conduct](.github/CODE_OF_CONDUCT.md)** for more information.

&nbsp;

---

## License

This library is open-source software licensed under the [MIT license](LICENSE).

&nbsp;

&nbsp;

<div align="center">
    <a href="#top">&uarr;<br> TOP </a> 
</div>

<!-- 
# COPYRIGHT
######################################################### -->
<h2></h2>
<p align="center">
    <small>COPYRIGHT &copy; 2024 JAMES GOBER &amp; JAMESGOBER.COM.</small>
</p>

&nbsp;


<!-- 
# LINKS
######################################################### -->
[Contribution Guidelines]: .github/CONTRIBUTING.md
[CONTRIBUTING]:            .github/CONTRIBUTING.md
[CODE OF CONDUCT]:         .github/CODE_OF_CONDUCT.md
[REPORT SECURITY ISSUES]:  .github/SECURITY.md
[SECURITY POLICY]:         .github/SECURITY.md
[SECURITY]:                .github/SECURITY.md