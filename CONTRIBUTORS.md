<h1 align="center">
    <picture picture>
        <source media="(prefers-color-scheme: dark)" srcset="./docs/media/jamesgober-logo-dark.png">
        <img width="72" height="72" alt="Official brand mark and logo of James Gober. Image shows JG stylish initials encased in a hexagon outline." src="./docs/media/jamesgober-logo.png">
    </picture>
    <br>
    <b>CONTRIBUTORS</b>
    <br>
    <sup>GUIDELINES &amp; SUPPORT</sup>
    <br>
</h1>

Thank you for considering contributing to **JG\Config**! Contributions of all types are welcome and help improve the project. This guide outlines how you can participate effectively.

&nbsp;

&nbsp;

## **How to Contribute**

### 1. Bug Reports and Feature Requests
- **Bug Reports**:
  - Use the [GitHub Issues](https://github.com/jamesgober/config/issues) page to report bugs.
  - Include clear details such as steps to reproduce, expected behavior, and the actual result.
- **Feature Requests**:
  - Open a feature request on [GitHub Issues](https://github.com/jamesgober/config/issues).
  - Provide a description of the feature, its purpose, and potential use cases.


##

&nbsp;

### 2. Contributing Code
#### **Setup**
1. Fork the repository and clone it locally.
2. Install dependencies using `composer install`.
3. Write your code, ensuring it adheres to the existing structure and coding standards.

#### **Coding Standards**
- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) guidelines.
- Include detailed PHPDoc comments for all methods, classes, and significant sections of the code.
- Avoid using third-party libraries unless explicitly approved.

#### **Testing**
- Run tests using `vendor/bin/phpunit`.
- Add new tests for any new functionality or bug fixes.

##

&nbsp;


### 3. Submitting Your Changes
1. Push your changes to your forked repository.
2. Open a pull request on the main repository.
3. Ensure your pull request:
   - Describes the changes made.
   - References any related issues.
   - Includes passing tests.

##

&nbsp;


### 4. Other Ways to Contribute
- Review open pull requests and provide constructive feedback.
- Help update documentation.
- Share the project with others.

&nbsp;

&nbsp;

## Coding Standards

Our library follows strict coding and design principles to maintain high-quality code and performance. Please ensure your contributions adhere to the following guidelines:

&nbsp;

#### **General Guidelines**
- Follow the **[PSR-12](https://www.php-fig.org/psr/psr-12/)** coding standards for consistent and modern PHP code style.
- Write **clean, maintainable, and efficient** code, with performance and extensibility in mind.
- Use **detailed PHPDoc comments** for all methods, classes, and properties, ensuring clarity for other contributors.

&nbsp;

#### **Design Principles**
We adhere to the following principles to maintain code quality:
1. **S.O.L.I.D Principles**:
   - **S**ingle Responsibility: Each class should have a single purpose.
   - **O**pen/Closed: Code should be open for extension but closed for modification.
   - **L**iskov Substitution: Objects should be replaceable by their base types without altering correctness.
   - **I**nterface Segregation: Use specific interfaces rather than forcing a single, general-purpose one.
   - **D**ependency Injection: Prefer constructor injection for dependencies.

&nbsp;

2. **Performance and Efficiency**:
   - Avoid unnecessary loops, function calls, or memory usage.
   - Optimize file parsing and data processing for large configurations.
   - Prioritize reducing overhead in frequently called operations.

&nbsp;

3. **Extensibility and Modularity**:
   - Write reusable components that integrate seamlessly with the existing library.
   - Ensure new features don't introduce breaking changes to public APIs.

#### **Examples**
Here’s how we expect contributors to approach code design:

&nbsp;

**Do**:
```php
class ConfigLoader
{
    public function loadFile(string $filePath): array
    {
        // Perform validation and return parsed content
    }
}
```

&nbsp;

**Avoid**:
```php
class ConfigLoader
{
    public function load(string $filePath)
    {
        // Process file without returning structured data or validation
    }
}
```

&nbsp;

&nbsp;

## **Community Guidelines**
Respect all contributors and engage in productive discussions. If disputes arise, maintain professionalism.

&nbsp;

&nbsp;

Happy coding, and thank you for your support!

##

<p>
    CONTRIBUTORS: &nbsp; v1.0.0
    <br>
    <small>Updated: December 5th 2024</small>
</p>
