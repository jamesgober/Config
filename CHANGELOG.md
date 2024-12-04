<h1 align="center" id="top">
    <b>CHANGELOG</b>
</h1>

This file tracks all notable changes made to the project, including new features, improvements, and bug fixes. This changelog follows the principles of [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), ensuring clarity and consistency. Versioning adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


- **Added**: For new features or functionality.
- **Changed**: For changes in existing functionality.
- **Deprecated**: For soon-to-be removed features.
- **Removed**: For now removed features.
- **Fixed**: Bug fixes and performance improvements.
- **Security**: In case of vulnerabilities.

---
<!--
## [Unreleased]

### Added
- Created a paragraph with a link to the `CHANGELOG` in the `README.md` file.
-->

&nbsp;

<!-- 
# 
----------------------------- -->
## [1.0.0-RC.2] - 2024-12-03

### Fixed
- Resolved issues with the `load` method in `Config.php`.

### Updated
- `Delete` method in `Config.php` now deletes configurations by group, ensuring consistency.
- Enhanced `ConfigParserFactory.php` with support for `yaml` and `yml` files, improving YAML compatibility.
- Updated `README.md` file.

&nbsp;

<!-- 
# 
----------------------------- -->
## [1.0.0-RC.1] - 2024-11-22

### Added
- Initial setup of project files: `LICENSE`, `VERSION`, `composer.json`, `.gitignore`, `.gitattributes`, and `CHANGELOG.md`.
- Created Main `Config.php` class.
- Created `ConfigParserFactory.php` class.
- Created `ConfigException.php` class.
- Created `ConfigParseException.php` class.
- Created `InvalidParserException.php` class.
- Created `ParserInterface.php` class.
- Created `PhpParser.php` class.
- Created `JsonParser.php` class.
- Created `IniParser.php` class.
- Created `ConfParser.php` class.
- Created `XmlParser.php` class.
- Created `YamlParser.php` class.
- Created `README.md` file.

<!-- 
# UNRELEASED
----------------------------- -->
[unreleased]: https://github.com/jamesgober/Config/compare/v1.0.0-RC.1...HEAD

[1.0.0-RC.2]: https://github.com/jamesgober/Config/compare/v1.0.0-RC.1...v1.0.0-RC.2
[1.0.0-RC.1]: https://github.com/jamesgober/Config/releases/tag/v1.0.0-RC.1