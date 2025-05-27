## Table of contents
- [Rudra\Validation\Validation](#rudra_validation_validation)
- [Rudra\Validation\ValidationFacade](#rudra_validation_validationfacade)
- [Rudra\Validation\ValidationInterface](#rudra_validation_validationinterface)
<hr>

<a id="rudra_validation_validation"></a>

### Class: Rudra\Validation\Validation
##### implements [Rudra\Validation\ValidationInterface](#rudra_validation_validationinterface)
| Visibility | Function |
|:-----------|:---------|
|public|<em><strong>run</strong>(): array</em><br>|
|public|<em><strong>approve</strong>( array $data ): bool</em><br>|
|public|<em><strong>getValidated</strong>( array $data  array $excludedKeys ): array</em><br>|
|public|<em><strong>getAlerts</strong>( array $data  array $excludedKeys ): array</em><br>|
|private|<em><strong>removeExcluded</strong>( array $inputArray  array $excludedKeys ): array</em><br>|
|public|<em><strong>set</strong>(  $verifiable ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>sanitize</strong>( string $verifiable  array|string|null $allowableTags ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>email</strong>( string $verifiable  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>required</strong>( string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>integer</strong>( string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>min</strong>(  $length  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>max</strong>(  $length  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>equals</strong>(  $verifiable  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|public|<em><strong>csrf</strong>( array $csrfSession   $message ): Rudra\Validation\ValidationInterface</em><br>|
|private|<em><strong>validate</strong>( bool $bool  string $message ): Rudra\Validation\ValidationInterface</em><br>|


<a id="rudra_validation_validationfacade"></a>

### Class: Rudra\Validation\ValidationFacade
| Visibility | Function |
|:-----------|:---------|
|public static|<em><strong>__callStatic</strong>( string $method  array $parameters ): mixed</em><br>|


<a id="rudra_validation_validationinterface"></a>

### Class: Rudra\Validation\ValidationInterface
| Visibility | Function |
|:-----------|:---------|
|abstract public|<em><strong>run</strong>(): array</em><br>|
|abstract public|<em><strong>approve</strong>( array $data ): bool</em><br>|
|abstract public|<em><strong>getValidated</strong>( array $data  array $excludedKeys ): array</em><br>|
|abstract public|<em><strong>getAlerts</strong>( array $data  array $excludedKeys ): array</em><br>|
|abstract public|<em><strong>set</strong>(  $verifiable ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>sanitize</strong>( string $verifiable  array|string|null $allowableTags ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>email</strong>( string $verifiable  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>required</strong>( string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>integer</strong>( string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>min</strong>(  $length  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>max</strong>(  $length  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>equals</strong>(  $verifiable  string $message ): Rudra\Validation\ValidationInterface</em><br>|
|abstract public|<em><strong>csrf</strong>( array $csrfSession   $message ): Rudra\Validation\ValidationInterface</em><br>|
<hr>

###### created with [Rudra-Documentation-Collector](#https://github.com/Jagepard/Rudra-Documentation-Collector)
