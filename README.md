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

<div style="text-align: center;">
    <img src="https://img.shields.io/github/stars/jamesgober/config?style=flat" alt="GitHub Stars"> &nbsp; 
    <img src="https://img.shields.io/github/issues/jamesgober/config?style=flat" alt="GitHub Issues"> &nbsp;  
    <img src="https://img.shields.io/github/v/release/jamesgober/config?display_name=tag&style=flat" alt="GitHub Release"> &nbsp; 
    <img src="https://img.shields.io/github/license/jamesgober/config?style=flat" alt="GitHub License"> &nbsp;
    <img src="https://img.shields.io/badge/PHP-8.2-blue?style=flat" alt="PHP Version">
</div>

&nbsp;

<h2>A Flexible PHP Configuration Manager</h2>

Config is a lightweight yet powerful PHP library designed to handle your application's configuration needs with ease and efficiency. Supporting multiple formats like JSON, XML, YAML, INI, and PHP arrays out of the box, it provides the flexibility to add custom parsers tailored to your specific requirements. Config offers a modular design, efficient caching capabilities, and a straightforward API to streamline configuration management in modern PHP projects.


&nbsp;


## Key Features

- **Multi-Format Support**: &nbsp; Seamlessly load configurations from JSON, XML, YAML, INI, and PHP array files.
- **Custom Parsers**: &nbsp; Extend functionality by adding support for any file format with ease.
- **Organized Management**: &nbsp; Efficiently manage configuration data with a modular and extendable design.
- **Robust Error Handling**: &nbsp; Custom exceptions provide clear and actionable feedback for debugging.
- **Lightweight and Intuitive**: &nbsp; A simple yet powerful API designed for minimal overhead and ease of use.


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

---

&nbsp;

## Quick Start

### Loading Configuration Files

```php
use JG\Config\Config;

$config = new Config('/path/to/config/files');

// Load a configuration file
$config->load('database.json');
$config->load('app.xml');

// Access configuration values
$dbHost = $config->get('database.host', 'default_host');

// Check if a configuration key exists
if ($config->has('app.debug')) {
    echo "Debug mode is enabled!";
}

// Add or update configuration values
$config->add('app.name', 'MyApp');
```


### Delete Configuration
```php
/*
Config Data
[
    app.name = 'Application Name',
    app.version = '1.0.0',
    app.debug = true,
    ...
]
*/

// Delete single value
$config->delete('app.version'); // app.version deleted

// Delete entire group
$config->delete('app'); // app.name, app.debug... deleted
```

### Custom Parsers

You can extend the library by registering custom parsers:

```php
use JG\Config\ConfigParserFactory;

// Register a custom parser for `.custom` files
ConfigParserFactory::registerParser('custom', MyCustomParser::class);
```

&nbsp;

### Supported Formats
- **CONF**: `config.conf`
- **INI**: `config.ini`
- **JSON**: `config.json`
- **PHP Array**: `config.php`
- **XML**: `config.xml`
- **YAML**: `config.yaml` or `config.yml`

#### config.php
```php
<?php
return [
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'password' => 'secret',
];
```

#### config.json
```json
{
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "secret"
}
```

#### config.ini
```ini
[app]
name    = "TestApp"
version = "1.0.0"
debug   = true
cache   = null
```

#### config.xml
```xml
<app>
    <name>TestApp</name>
    <version>1.0.0</version>
    <debug>true</debug>
    <cache>null</cache>
</app>
```

#### config.yaml
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

## Error Handling

The library uses custom exceptions for error management:

- `JG\Config\Exceptions\ConfigException`: Base exception for all configuration-related errors.
- `JG\Config\Exceptions\ConfigParseException`: Thrown when a configuration file cannot be parsed.
- `JG\Config\Exceptions\InvalidParserException`: Thrown when an invalid or unsupported parser is encountered.

Example:
```php
try {
    $config->load('missing_file.json');
} catch (ConfigException $e) {
    echo "Error loading configuration: " . $e->getMessage();
}
```


---

## Directory Structure

```
src/
├── Config.php
├── ConfigParserFactory.php
├── Exceptions/
│   ├── ConfigException.php
│   ├── ConfigParseException.php
│   └── InvalidParserException.php
├── Parsers/
│   ├── ParserInterface.php
│   ├── PhpParser.php
│   ├── JsonParser.php
│   ├── IniParser.php
│   ├── XmlParser.php
│   └── YamlParser.php
tests/
...
```

---

## Contributing

We welcome contributions! Please submit pull requests or report issues on [GitHub](https://github.com/jamesgober/Config).

---

## **Changelog**
For a detailed list of changes and updates in each version, please refer to the [CHANGELOG](CHANGELOG.md).

---

## License

This library is open-source software licensed under the [MIT license](LICENSE).



&nbsp;

<h2></h2>
<p align="center"><small>COPYRIGHT &copy; 2024 JAMES GOBER.</small></p>

<div align="center">
    <a href="#top">&uarr;<br>TOP</a>
</div>

&nbsp;
