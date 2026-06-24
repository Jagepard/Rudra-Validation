[![PHPunit](https://github.com/Jagepard/Rudra-Validation/actions/workflows/php.yml/badge.svg)](https://github.com/Jagepard/Rudra-Validation/actions/workflows/php.yml)
[![Maintainability](https://qlty.sh/badges/93cd20b9-7b49-45c0-ab12-23381185d28f/maintainability.svg)](https://qlty.sh/gh/Jagepard/projects/Rudra-Validation)
[![CodeFactor](https://www.codefactor.io/repository/github/jagepard/rudra-validation/badge)](https://www.codefactor.io/repository/github/jagepard/rudra-validation)
[![Coverage Status](https://coveralls.io/repos/github/Jagepard/Rudra-Validation/badge.svg?branch=master)](https://coveralls.io/github/Jagepard/Rudra-Validation?branch=master)
-----

# Rudra-Validation | [API](https://github.com/Jagepard/Rudra-Validation/blob/master/docs.md "Documentation API")
### Installation
```
composer require rudra/validation
```
## Features
- Fluent Interface for chain validation
- Built-in sanitization (HTML tags, trimming)
- CSRF protection
- Custom validation rules
- Human-readable error messages with field aliases
- Strict type checking
## Available Rules

| Rule | Description | Example |
|------|-------------|---------|
| `required()` | Field must not be empty | `->required()` |
| `min(int $length)` | Minimum string length | `->min(3)` |
| `max(int $length)` | Maximum string length | `->max(255)` |
| `email()` | Valid email address | `->email()` |
| `url()` | Valid URL | `->url()` |
| `integer()` | Integer value | `->integer()` |
| `numeric()` | Numeric value (int or float) | `->numeric()` |
| `between(int\|float $min, int\|float $max)` | Value in range | `->between(1, 100)` |
| `equals(mixed $value)` | Strict equality | `->equals('password123')` |
| `in(array $allowed)` | Value in allowed list | `->in(['admin', 'user'])` |
| `regex(string $pattern)` | Match regex pattern | `->regex('/^[A-Z]{3}$/')` |
| `date(string $format)` | Valid date format | `->date('Y-m-d')` |
| `csrf(array $tokens)` | CSRF token validation | `->csrf($_SESSION['csrf'])` |
| `custom(callable $callback)` | Custom validation | `->custom(fn($v) => strlen($v) > 5)` |
| `sanitize(string $value)` | Strip HTML tags | `->sanitize($input, '<p><a>')` |
### Example of usage
```php
use Rudra\Validation\ValidationFacade;

$_SESSION['csrf'][] = '123456';

$processed = [
    'set_without_validation' => ValidationFacade::set('set_without_validation')->run(),
    'set_with_data_clearing' => ValidationFacade::sanitize(' <p>String</p> ')->run(),
    
    'required' => ValidationFacade::set('required')->required()->run(),
    'integer'  => ValidationFacade::set(12345)->required()->integer()->run(),
    'minimum'  => ValidationFacade::set('12345')->required()->min(5)->run(),
    'maximum'  => ValidationFacade::set('12345')->required()->max(5)->run(),
    'equals'   => ValidationFacade::set('12345')->equals('12345')->run(),
    'email'    => ValidationFacade::email('user@example.com')->run(),
    'csrf'     => ValidationFacade::set('123456')->csrf($_SESSION['csrf'])->run()
];
```
Data is validated in a chain
### For example:
```php
ValidationFacade::sanitize(' <p>12345</p> ')->required()->min(3)->max(10)->equals('12345')->run();
ValidationFacade::email('user@example.com')->max(25)->run();
```
### Data validation check
```php
if (ValidationFacade::approve($processed)) {
    $validated = ValidationFacade::getValidated($processed, ["csrf", "_method"]);
}
```
##### getValidated
Gets an array of validated data excluding the keys ["csrf", "_method"]
### Get all error messages
```php
ValidationFacade::getErrors($processed, ['required']);
```
##### getAlerts
Gets an array with error messages excluding the keys ['required']
### Using field aliases

```php
use Rudra\Validation\ValidationFacade;

ValidationFacade::setAliases([
    'usr_name' => 'Username',
    'usr_email' => 'Email Address'
]);

$processed = [
    'usr_name'  => ValidationFacade::set($_POST['usr_name'])->required()->min(3)->run(),
    'usr_email' => ValidationFacade::email($_POST['usr_email'])->run(),
];

$errors = ValidationFacade::getErrors($processed);
// Returns: ['usr_name' => ['msg' => 'Поле должно быть заполнено', 'alias' => 'Username'], ...]
```
## License

This project is licensed under the **Mozilla Public License 2.0 (MPL-2.0)** — a free, open-source license that:

- Requires preservation of copyright and license notices,
- Allows commercial and non-commercial use,
- Requires that any modifications to the original files remain open under MPL-2.0,
- Permits combining with proprietary code in larger works.

📄 Full license text: [LICENSE](./LICENSE)  
🌐 Official MPL-2.0 page: https://mozilla.org/MPL/2.0/
