## Table of contents

- [\Rudra\Validation](#class-rudravalidation)
- [\Rudra\Interfaces\ValidationInterface (interface)](#interface-rudrainterfacesvalidationinterface)

<hr /><a id="class-rudravalidation"></a>
### Class: \Rudra\Validation

> Class Validation

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\Rudra\Interfaces\ContainerInterface</em> <strong>$container</strong>, <em>null</em> <strong>$captchaSecret=null</strong>)</strong> : <em>void</em><br /><em>Validation constructor.</em> |
| public | <strong>access(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>bool</em> |
| public | <strong>captcha(</strong><em>mixed</em> <strong>$data</strong>, <em>string</em> <strong>$message=`'Пожалуйста заполните поле :: reCaptcha'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>csrf(</strong><em>string</em> <strong>$message=`'csrf'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>email(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Email указан неверно'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>equals(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Пароли не совпадают'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>flash(</strong><em>mixed</em> <strong>$data</strong>, <em>mixed</em> <strong>$excludedKeys</strong>)</strong> : <em>array</em> |
| public | <strong>get(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>array</em> |
| public | <strong>hash(</strong><em>\string</em> <strong>$salt=null</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>integer(</strong><em>\string</em> <strong>$message=`'Необходимо указать число'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>maxLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Указано слишком много символов'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>minLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Указано слишком мало символов'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>required(</strong><em>\string</em> <strong>$message=`'Необходимо заполнить поле'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>run()</strong> : <em>array</em> |
| public | <strong>sanitize(</strong><em>\string</em> <strong>$data</strong>, <em>null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Очищает входящие параметры от ненужных данных</em> |
| public | <strong>set(</strong><em>mixed</em> <strong>$data</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Устанавливаем данные без обработки</em> |
| protected | <strong>getResult(</strong><em>array</em> <strong>$result</strong>, <em>array</em> <strong>$excludedKeys</strong>)</strong> : <em>array</em> |
| protected | <strong>reset()</strong> : <em>void</em> |
| protected | <strong>validate(</strong><em>bool/\boolean</em> <strong>$bool</strong>, <em>\string</em> <strong>$message</strong>)</strong> : <em>\Rudra\$this</em> |

*This class implements [\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)*

<hr /><a id="interface-rudrainterfacesvalidationinterface"></a>
### Interface: \Rudra\Interfaces\ValidationInterface

> Interface ValidationInterface

| Visibility | Function |
|:-----------|:---------|
| public | <strong>access(</strong><em>mixed/array</em> <strong>$data</strong>)</strong> : <em>bool</em><br /><em>Проверяет все результаты собранные в массив</em> |
| public | <strong>captcha(</strong><em>mixed</em> <strong>$data</strong>, <em>string</em> <strong>$message=`'Пожалуйста заполните поле :: reCaptcha'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет верность заполнения капчи в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>csrf(</strong><em>string</em> <strong>$message=`'csrf'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет верность данных csrf защиты в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>email(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Email указан неверно'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет email на соответствие в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>equals(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Пароли не совпадают'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет эквивалентность введенных данных в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>flash(</strong><em>mixed</em> <strong>$data</strong>, <em>mixed</em> <strong>$excludedKeys</strong>)</strong> : <em>mixed</em><br /><em>Возвращает массив ошибок исключая при этом элементы массива $excludedKeys</em> |
| public | <strong>get(</strong><em>mixed/array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>mixed</em><br /><em>Возвращает обработанные и проверенные данные исключая при этом элементы массива $excludedKeys</em> |
| public | <strong>hash(</strong><em>\string</em> <strong>$salt=null</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет необходимость заполнения поля - не меннее 1 символа, в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>integer(</strong><em>\string</em> <strong>$message=`'Необходимо указать число'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет являются ли данные числом, в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>maxLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Указано слишком много символов'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет соответствуют ли данные максимальной длинне, в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>minLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Указано слишком мало символов'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Проверяет соответствуют ли данные минимальной длинне, в случае прохождения результат проверки передается далее, если нет, то передает сообщение об ошибке в $this->message и $this->res = false</em> |
| public | <strong>required(</strong><em>\string</em> <strong>$message=`'Необходимо заполнить поле'`</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em> |
| public | <strong>run()</strong> : <em>array</em><br /><em>Собирает результат работы методов класса</em> |
| public | <strong>sanitize(</strong><em>\string</em> <strong>$data</strong>, <em>null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Очищает входящие параметры от ненужных данных</em> |
| public | <strong>set(</strong><em>mixed</em> <strong>$data</strong>)</strong> : <em>[\Rudra\Interfaces\ValidationInterface](#interface-rudrainterfacesvalidationinterface)</em><br /><em>Устанавливаем данные без обработки</em> |

