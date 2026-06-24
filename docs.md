## Table of contents
- [Rudra\Validation\Validation](#rudra_validation_validation)
- [Rudra\Validation\ValidationFacade](#rudra_validation_validationfacade)
- [Rudra\Validation\ValidationInterface](#rudra_validation_validationinterface)


---



<a id="rudra_validation_validation"></a>

### Class: Rudra\Validation\Validation
| Visibility | Function |
|:-----------|:---------|
| public | `run(): array`<br>Returns the result of the check as an array.<br>The first element is the validated value (or false), the second is the error message (or null).<br>Resets the internal state for the next validation chain. |
| public | `approve(array $data): bool`<br>Checks an array of results for errors.<br>Returns true if all elements are successful (the first value in each subarray === true). |
| public | `getValidated(array $data, array $excludedKeys): array`<br>Extracts validated values from the data array.<br>Returns an associative array where keys are field names and values are the validated data.<br>Excludes the specified keys if they are passed. |
| public | `setAliases(array $aliases): void`<br>Sets aliases for fields used in validation.<br>Aliases are applied in the getErrors method to form human-readable field names in error messages. |
| public | `getErrors(array $data, array $excludedKeys): array`<br>Extracts error messages from validation data.<br>Returns an associative array where the key is the field name,<br>and the value is an array containing the error message and the field alias. |
| private | `removeExcluded(array $inputArray, array $excludedKeys): array`<br>Removes the specified keys from the array and returns the cleaned array. |
| public | `set(mixed $verifiable): Rudra\Validation\ValidationInterface`<br>Sets the value to be checked (validated). |
| public | `sanitize(string $verifiable, array\|string\|null $allowableTags): Rudra\Validation\ValidationInterface`<br>Cleans the input string from HTML tags (with the option to allow certain tags)<br>and saves the result for further checking. |
| public | `email(string $verifiable, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the specified string is a valid email address.<br>Saves the result of the check and sets an error message if the email is invalid. |
| public | `required(string $message): Rudra\Validation\ValidationInterface`<br>Checks if the field is filled (not an empty string).<br>If the value is missing or consists of spaces — sets the specified error message. |
| public | `min(int $length, string $message): Rudra\Validation\ValidationInterface`<br>Checks that the string length is not less than the specified value.<br>Sets an error message if the check fails. |
| public | `max(int $length, string $message): Rudra\Validation\ValidationInterface`<br>Checks that the string length does not exceed the specified value.<br>Sets an error message if the check fails. |
| public | `equals(mixed $verifiable, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value matches the specified one.<br>Uses strict comparison. |
| public | `csrf(array $csrfSession, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is contained in the array of valid CSRF tokens.<br>Used for protection against cross-site request forgery (CSRF). |
| public | `url(string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is a valid URL.<br>Sets the specified error message if the check fails. |
| public | `numeric(string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is numeric (integer or floating-point number).<br>Sets the specified error message if the check fails. |
| public | `integer(string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is a valid integer (not a float or string representation of a float).<br>Sets the specified error message if the check fails. |
| public | `between(int\|float $min, int\|float $max, string $message): Rudra\Validation\ValidationInterface`<br>Checks that the numeric value is within the specified range (inclusive).<br>Sets the specified error message if the value is outside the range or not numeric. |
| public | `regex(string $pattern, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value matches the specified regular expression pattern.<br>Sets the specified error message if the pattern does not match. |
| public | `date(string $format, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is a valid date in the specified format.<br>Uses strict comparison to avoid ambiguous date interpretations.<br>Sets the specified error message if the date is invalid. |
| public | `custom(callable $callback, string $message): Rudra\Validation\ValidationInterface`<br>Performs a custom validation using a user-defined callback function.<br>The callback receives the current value and must return true or false.<br>Sets the specified error message if the callback returns false. |
| public | `in(array $allowed, string $message): Rudra\Validation\ValidationInterface`<br>Checks if the current value is in the allowed list. |
| private | `validate(bool $condition, string $message): Rudra\Validation\ValidationInterface`<br>Performs a condition check and saves the validation result.<br>If the check fails, sets an error message. |


<a id="rudra_validation_validationfacade"></a>

### Class: Rudra\Validation\ValidationFacade
| Visibility | Function |
|:-----------|:---------|
| public static | `__callStatic(string $method, array $parameters): mixed`<br>Handles static method calls for the Facade class<br>It dynamically resolves the underlying class name by removing "Facade" from the class name<br>If the resolved class does not exist, it attempts to clean up the class name by removing spaces<br>If the resolved class is not already registered in the container, it registers it<br>Finally, it delegates the static method call to the resolved class instance |


<a id="rudra_validation_validationinterface"></a>

### Class: Rudra\Validation\ValidationInterface
| Visibility | Function |
|:-----------|:---------|
| abstract public | `run(): array`<br> |
| abstract public | `approve(array $data): bool`<br> |
| abstract public | `getValidated(array $data, array $excludedKeys): array`<br> |
| abstract public | `getErrors(array $data, array $excludedKeys): array`<br> |
| abstract public | `setAliases(array $aliases): void`<br> |
| abstract public | `set(mixed $verifiable): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `sanitize(string $verifiable, array\|string\|null $allowableTags): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `email(string $verifiable, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `required(string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `min(int $length, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `max(int $length, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `equals(mixed $verifiable, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `csrf(array $csrfSession, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `url(string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `numeric(string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `integer(string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `between(int\|float $min, int\|float $max, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `regex(string $pattern, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `date(string $format, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `custom(callable $callback, string $message): Rudra\Validation\ValidationInterface`<br> |
| abstract public | `in(array $allowed, string $message): Rudra\Validation\ValidationInterface`<br> |


---

###### created with [Rudra-Documentation-Collector](https://github.com/Jagepard/Rudra-Documentation-Collector)
