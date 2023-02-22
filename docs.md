## Table of contents

- [\Rudra\Validation\Validation](#class-rudravalidationvalidation)
- [\Rudra\Validation\ValidationFacade](#class-rudravalidationvalidationfacade)
- [\Rudra\Validation\ValidationInterface (interface)](#interface-rudravalidationvalidationinterface)

<hr /><a id="class-rudravalidationvalidation"></a>

### Class: \Rudra\Validation\Validation

| Visibility | Function |
|:-----------|:---------|
| public | <br><strong>approve(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>boolean</em><hr /><em>Checks if all elements of an array are validated Array<br> example: `$processed = [ 'csrf_field' => Validation::sanitize($inputData["csrf_field"])->csrf(Session::get("csrf_token"))->run(), 'search'     => Validation::sanitize($inputData["search"])->min(1)->max(50)->run(), 'redirect'   => Validation::sanitize($inputData["redirect"])->max(500)->run(), ];` <hr> Проверяет все ли элементы массива прошли проверку</em> |
| public | <br><strong>csrf(</strong><em>array</em> <strong>$csrfSession</strong>, <em>string</em> <strong>$message = 'csrf'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Cross-Site Request Forgery Protection <hr> Защита от межсайтовой подделки запроса</em> |
| public | <br><strong>email(</strong><em>string</em> <strong>$verifiable</strong>, <em>string</em> <strong>$message = 'Email is invalid'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em><br>Sets the data before checking that the value is a valid e-mail.<br> Sets the status to false and an error message if validation fails <hr> Устанавливает данные предварительно проверяя, что значение является корректным e-mail <br>Устанавливает статус false и сообщение об ошибке, если проверка не пройдена</em> |
| public | <br><strong>equals(</strong><em>\Rudra\Validation\[type]</em> <strong>$verifiable</strong>, <em>\string</em> <strong>$message = 'Values ​​do not match'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Compares the equivalence of `$verifiable` and `$this->verifiable` values <hr> Сравнивает эквивалентность значений `$verifiable` и `$this->verifiable`</em> |
| public | <br><strong>getAlerts(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys = []</strong>)</strong> : <em>array</em><hr /><em>Receives messages about non-compliance with validation requirements `$excludedKeys` allows you to exclude elements which are not required after verification example: `Validation::getAlerts($processed, ["_method"]);` <hr> Получает сообщения о несоответствии требованиям валидации `$excludedKeys` позволяет исключить элементы, которые не требуются после проверки</em> |
| public | <br><strong>getValidated(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys = []</strong>)</strong> : <em>array</em><hr /><em>Get an array of validated data $excludedKeys allows you to exclude elements which are not required after verification example: Validation::getValidated($processed, ["csrf_field", "_method"]); <hr> Получить массив данных прошедших проверку $excludedKeys позволяет исключить элементы, которые не требуются после проверки</em> |
| public | <br><strong>integer(</strong><em>\string</em> <strong>$message=`'Number is required'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Finds whether a $this->verifiable is a number or a numeric string <hr> Проверяет, является ли $this->verifiable числом или строкой, содержащей число</em> |
| public | <br><strong>max(</strong><em>\Rudra\Validation\[type]</em> <strong>$length</strong>, <em>\string</em> <strong>$message=`'Too many characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Checks the string value in $this->verifiable against the maximum allowed number of characters <hr> Проверяет строковое значение в $this->verifiable на максимально допустимое количество символов</em> |
| public | <br><strong>min(</strong><em>\Rudra\Validation\[type]</em> <strong>$length</strong>, <em>\string</em> <strong>$message=`'Too few characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Checks the string value in $this->verifiable against the minimum allowed number of characters <hr> Проверяет строковое значение в $this->verifiable на минимально допустимое количество символов</em> |
| public | <br><strong>required(</strong><em>\string</em> <strong>$message=`'You must fill in the field'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Checks if a string value is set in $this->verifiable <hr> Проверяет установлено ли строковое значение в $this->verifiable</em> |
| public | <br><strong>run()</strong> : <em>array</em><hr /><em>Выдает массив с результатом проверки в случае успешной проверки: [$this->verifiable // проверенные данные, null // вместо сообщения об ошибке] в случае если данные не соответствуют требованиям [false // вместо проверенных данных, $this->message // сообщение о несоответствии] <hr> Gives an array with the result of the check in case of successful check: [$this->verifiable // verified data, null instead of error message] in case the data does not meet the requirements [false // instead of validated data, $this->message // mismatch message]</em> |
| public | <br><strong>sanitize(</strong><em>\string</em> <strong>$verifiable</strong>, <em>\null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Sets the data to be checked with processing for strings with valid tags: $allowableTags <hr> Устанавливает проверяемые данные с обработкой для строк с указанием допустимых тегов: $allowableTags</em> |
| public | <br><strong>set(</strong><em>\Rudra\Validation\[type]</em> <strong>$verifiable</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><hr /><em>Sets the data to be checked without processing <hr> Устанавливает проверяемые данные без обработки</em> |

*This class implements [\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)*

<hr /><a id="class-rudravalidationvalidationfacade"></a>

### Class: \Rudra\Validation\ValidationFacade

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>__callStatic(</strong><em>mixed</em> <strong>$method</strong>, <em>array</em> <strong>$parameters=array()</strong>)</strong> : <em>void</em> |

<hr /><a id="interface-rudravalidationvalidationinterface"></a>

### Interface: \Rudra\Validation\ValidationInterface

| Visibility | Function |
|:-----------|:---------|
| public | <strong>approve(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>boolean</em><br />|
| public | <strong>csrf(</strong><em>array</em> <strong>$csrfSession</strong>, <em>string</em> <strong>$message = 'csrf'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br />|
| public | <strong>email(</strong><em>\string</em> <strong>$data</strong>, <em>\string</em> <strong>$message = 'Email is invalid'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br />|
| public | <strong>equals(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message = 'Values do not match'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br /> |
| public | <strong>getAlerts(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>array</em><br />|
| public | <strong>getValidated(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>array</em><br />|
| public | <strong>integer(</strong><em>\string</em> <strong>$message='Number is required'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br />|
| public | <strong>max(</strong><em>\Rudra\Validation\[type]</em> <strong>$length</strong>, <em>\string</em> <strong>$message='Too many characters specified'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br /> |
| public | <strong>min(</strong><em>\Rudra\Validation\[type]</em> <strong>$length</strong>, <em>\string</em> <strong>$message='Too few characters specified'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br /> |
| public | <strong>required(</strong><em>\string</em> <strong>$message='You must fill in the field'</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br />|
| public | <strong>run()</strong> : <em>array</em><br /> |
| public | <strong>sanitize(</strong><em>\string</em> <strong>$verifiable</strong>, <em>\null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em><br />|
| public | <strong>set(</strong><em>mixed</em> <strong>$data</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |

