<h1 align="center" id="top">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="./docs/media/jamesgober-logo-dark.png">
        <img width="72" height="72" alt="Official brand mark and logo of James Gober. Image shows JG stylish initials encased in a hexagon outline." src="./docs/media/jamesgober-logo.png">
    </picture>
    <br>
    <b>CHANGELOG</b>
</h1>

This file tracks all notable changes made to the project, including new features, improvements, and bug fixes. This changelog follows the principles of [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), ensuring clarity and consistency. Versioning adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

&nbsp;

### Valid Change Types
- **Added**: For new features or functionality.
- **Changed**: For changes in existing functionality.
- **Deprecated**: For soon-to-be removed features.
- **Removed**: For now removed features.
- **Fixed**: Bug fixes and performance improvements.
- **Security**: In case of vulnerabilities.

---
<!-- 
## [Unreleased]
-->





&nbsp;

<!--  
# CHANGELOG
----------------------------- -->
## [1.0.0] - 2024-12-06

### **Highlights**
The first stable release of **JG\Config** marks the culmination of extensive development, testing, and optimization. With a focus on flexibility, performance, and reliability, this version is ready to power modern PHP projects with advanced configuration management features.

---

### **Added**
- **Advanced Documentation**:
  - Introduced comprehensive markdown documentation, including dedicated files for `CONTRIBUTING`, `CODE_OF_CONDUCT`, `SECURITY`, and `ADVANCED` features.
  - Added a new `Design Philosophy` document to outline core principles.
  - Enhanced README to provide clear examples and quick-start guides.
- **Modular Directory Structure**:
  - Organized core library components, parsers, and tests for improved maintainability.
- **Mailto Integration**:
  - Embedded `mailto` links for issue reporting and security disclosures.
- **Support for Non-UTF-8 Encoding**:
  - Gracefully handles and converts non-UTF-8 encoded configuration files.

---

### **Changed**
- **Advanced Features Refactoring**:
  - Moved "Advanced Features" section from README into a dedicated `ADVANCED.md` file.
  - Improved clarity and accessibility for dynamic flattening, caching, custom parsers, and stream parsing.
- **Error Messaging**:
  - Improved clarity and consistency of error messages across parsers and file-handling operations.
- **Performance Enhancements**:
  - Optimized CONF parsing using regex for faster key-value extraction while maintaining readability.
- **Project Layout**:
  - Moved documentation files (e.g., CONTRIBUTING.md) into the `.github/` directory for cleaner repository structure.
  
---

### **Fixed**
- **Parser Stability**:
  - Addressed edge cases in `ConfigParserFactory` where incorrect file formats could cause unhandled exceptions.
- **Caching Bugs**:
  - Resolved inconsistencies in cache expiration handling and structure validation.
- **Flattening Edge Cases**:
  - Fixed bugs in the `flattenArray` logic to handle deeply nested configurations more reliably.
- **UTF-8 Enforcement**:
  - Fixed handling of files auto-converted to UTF-8 on certain servers.

---

### **Removed**
- **Redundant Test Parsers**:
  - Removed testing-related parsers from production for cleaner deployment.
- **Deprecated README Content**:
  - Streamlined README to focus on essential information; moved advanced topics to separate markdown files.

---

### **Documentation Updates**
- Full documentation now hosted directly in the repository:
  - **README.md**: Comprehensive introduction, quick-start guide, and installation instructions.
  - **ADVANCED.md**: Detailed insights into advanced features like caching, flattening, and custom parsers.
  - **DESIGN_PHILOSOPHY.md**: Explanation of the principles driving the library's design.
  - **SECURITY.md**: Instructions for responsibly reporting vulnerabilities.
  - **CONTRIBUTING.md**: Guidelines for contributing to the project.




&nbsp;

<!-- 
# 
----------------------------- -->
## [1.0.0-RC.5] - 2024-12-05

### Added
- **Custom Parser Support**: Introduced the ability to dynamically register and utilize custom parsers, enhancing extensibility.
- **Non-UTF-8 Handling**: Added improved handling for files with non-UTF-8 encodings, ensuring compatibility across varied environments.
- **Performance Tests**: Included benchmarks and stress tests to validate library performance under high-load scenarios.
- **Extended Cache Operations**: Enhanced cache handling with robust invalidation and expiration logic.
- **Enhanced Flattening Control**: Improved options for enabling/disabling key flattening, offering better flexibility for hierarchical configurations.

### Changed
- **Default Flattening Behavior**: Updated the `Config` constructor to accept an explicit flag for flattening, providing better control and eliminating unexpected defaults.
- **Parsing Enhancements**:
  - Optimized performance for **CONF parsing** by leveraging `file()` for efficient line-by-line processing while preserving spacing and comments.
  - Improved regex-based parsing to handle edge cases with clean and flexible key-value extraction.
  - Enhanced exception messages to offer more descriptive debugging feedback.
- **Error Handling**: Unified error handling across parsers to provide more informative and actionable exceptions.
- **Streamlining Tests**:
  - Refactored `ConfigTest` for clarity, expanded coverage, and consistent structure.
  - Reworked performance test cases to accurately benchmark operations under diverse conditions.

### Fixed
- **Custom Parser Registration**: Resolved issues where dynamically registered custom parsers were not recognized during runtime.
- **UTF-8 Validation**: Addressed conflicts where systems forced UTF-8 encoding, bypassing intended checks for invalid encoding.
- **Max Depth Exceeded**: Fixed edge cases in nested configurations exceeding max depth, ensuring the error is thrown correctly.
- **Cache Structure Validation**: Resolved potential inconsistencies in cache format validation and loading.

### Removed
- **Test Parsers from Production**: Completely removed test parsers from production code to maintain separation of concerns and a cleaner production environment.


&nbsp;

<!-- 
# 
----------------------------- -->
## [1.0.0-RC.4] - 2024-12-03
### Added
- **Maximum Depth Handling**:
  - Introduced a `private int $maxDepth` property to enforce a limit on the depth of nested configurations.
  - Default maximum depth is set to 10, ensuring stability and preventing performance issues with deeply nested structures.
- **Caching Features**:
  - Added `saveCache` method for saving the current configuration, groups, and expiration timestamp to a cache file.
  - Added `loadCache` method for loading configuration data from a cache file. Includes expiration validation.
  - Added `deleteCache` method for removing a specified cache file.
  - Added `isCacheLoaded` method to check if a valid cache has been successfully loaded.
- **Getter Methods**:
  - Added `getAll` method to retrieve the entire configuration array.
  - Added `getGroups` method to retrieve all grouped configurations.

### Updated
- **`flattenArray` Method**:
  - Improved functionality to handle deeply nested arrays safely by enforcing the `$maxDepth` limit.
  - Throws `ConfigException` if the maximum depth is exceeded.
- **PHPDoc Improvements**:
  - Added detailed PHPDoc comments for `private int $maxDepth` and the `flattenArray` method to clarify functionality and usage.
- **Configuration Loading**:
  - Enhanced `load` method to leverage caching and ensure more efficient and consistent configuration management.

### Fixed
- **Recursive Operations**:
  - Fixed potential issues with recursive operations in `flattenArray` by enforcing depth limits.
- **Grouped Configuration Handling**:
  - Resolved edge cases in `add` and `delete` methods for handling grouped configurations more effectively.
- **Caching Methods**:
  - Addressed file handling robustness in caching methods (`saveCache`, `loadCache`, `deleteCache`) for better error management.


&nbsp;

<!-- 
# 
----------------------------- -->
## [1.0.0-RC.3] - 2024-12-03

### Added
- Added support for managing flattened configurations in the `has` method of `Config.php`, enabling checks for deeply nested and dot-notated keys.
- Introduced the `flattenArray` method in `Config.php` to handle flattening of multidimensional arrays into dot-notated structures.
- Added support in the `add` method to dynamically update grouped configurations while maintaining consistency with flattened keys.
- Included support for deleting grouped keys using the `delete` method, ensuring proper cleanup of both configuration and group data.
- Created YmlParser.php class.

### Updated
- Refactored the `load` method in `Config.php` to integrate seamlessly with the `flattenArray` method, ensuring that flattened and non-flattened configurations are handled consistently.
- Improved the `add` method in `Config.php` to validate group existence and dynamically create groups as needed.
- Enhanced the `delete` method in `Config.php` to cleanly remove grouped keys while avoiding redundant loops.
- Revised test cases in `ConfigTest.php` to accurately reflect changes in flattened and non-flattened key behavior.
- Adjusted `ConfigTest.php` to assert correct handling of group deletion and flattened configurations.

### Fixed
- Fixed `testLoadValidFile` in `ConfigTest.php` to correctly assert flattened keys with the appropriate prefix.
- Fixed edge cases in the `add` method to avoid inconsistent state when adding non-dot-notated keys after groups have been initialized.
- Fixed potential issues in the `flattenArray` method where redundant merging could occur in nested arrays.

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
[unreleased]: https://github.com/jamesgober/Config/compare/v1.0.0...HEAD
<!-- 
# VERSIONS
----------------------------- -->

<!-- 
# PRE-RELEASE
----------------------------- -->
[1.0.0]: https://github.com/jamesgober/Config/compare/v1.0.0-RC.5...v1.0.0
[1.0.0-RC.5]: https://github.com/jamesgober/Config/compare/v1.0.0-RC.4...v1.0.0-RC.5
[1.0.0-RC.4]: https://github.com/jamesgober/Config/compare/v1.0.0-RC3...v1.0.0-RC.4
[1.0.0-RC.3]: https://github.com/jamesgober/Config/compare/v1.0.0-RC.2...v1.0.0-RC3
[1.0.0-RC.2]: https://github.com/jamesgober/Config/compare/v1.0.0-Rc.1...v1.0.0-RC.2
[1.0.0-RC.1]: https://github.com/jamesgober/Config/releases/tag/v1.0.0-RC.1