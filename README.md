[![PHPunit](https://github.com/Jagepard/Rudra-Validation/actions/workflows/php.yml/badge.svg)](https://github.com/Jagepard/Rudra-Validation/actions/workflows/php.yml)
[![Maintainability](https://qlty.sh/badges/93cd20b9-7b49-45c0-ab12-23381185d28f/maintainability.svg)](https://qlty.sh/gh/Jagepard/projects/Rudra-Validation)
[![CodeFactor](https://www.codefactor.io/repository/github/jagepard/rudra-validation/badge)](https://www.codefactor.io/repository/github/jagepard/rudra-validation)
[![Coverage Status](https://coveralls.io/repos/github/Jagepard/Rudra-Validation/badge.svg?branch=master)](https://coveralls.io/github/Jagepard/Rudra-Validation?branch=master)
-----

# Rudra-Validation | [API](https://github.com/Jagepard/Rudra-Validation/blob/master/docs.md "Documentation API")
### Install / Установка
```
composer require rudra/validation
```
### Example of usage / Пример использования 
```php
use Rudra\Validation\ValidationFacade;

$_SESSION['csrf'][] = '123456';

$processed = [
    'set_without_validation' => ValidationFacade::set('set_without_validation')->run();
    'set_with_data_clearing' => ValidationFacade::sanitize(' <p>String</p> ')->run();
    
    'required' => ValidationFacade::set('required')->required()->run(),
    'integer'  => ValidationFacade::set(12345)->required()->integer()->run(),
    'minimum'  => ValidationFacade::set('12345')->required()->min(5)->run();
    'maximum'  => ValidationFacade::set('12345')->required()->max(5)->run();
    'equals'   => ValidationFacade::set('12345')->equals('12345')->run();
    'email'    => ValidationFacade::email('user@example.com')->run();
    'csrf'     => ValidationFacade::set('123456')->csrf($_SESSION['csrf'])->run();
];
```
Data is validated in a chain
Данные проверяются по цепочке
### For example / Например
```php
ValidationFacade::sanitize(' <p>12345</p> ')->required()->min(3)->max(10)->equals('12345')->run();
ValidationFacade::email('user@example.com')->max(25)->run();
```
### Data validation check / Проверка валидности данных
```php
if (ValidationFacade::approve($processed)) {
    $validated = ValidationFacade::getValidated($processed, ["csrf", "_method"]);
}
```
##### getValidated
Gets an array of validated data excluding the keys ["csrf", "_method"]
Получает массив проверенных данных исключая ключи ["csrf", "_method"]
### Get all error messages / Получить все сообщения об ошибках
```php
ValidationFacade::getAlerts($processed, ['required']);
```
##### getAlerts
Gets an array with error messages excluding the keys ['required']
Получает массив с сообщениями об ошибках исключая ключи ['required']