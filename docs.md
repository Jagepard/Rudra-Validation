## Table of contents

- [\Rudra\Validation\ValidationInterface (interface)](#interface-rudravalidationvalidationinterface)
- [\Rudra\Validation\Validation](#class-rudravalidationvalidation)

<hr /><a id="interface-rudravalidationvalidationinterface"></a>
### Interface: \Rudra\Validation\ValidationInterface

| Visibility | Function |
|:-----------|:---------|
| public | <strong>captcha(</strong><em>bool</em> <strong>$captcha=false</strong>, <em>string</em> <strong>$secret=`''`</strong>, <em>string</em> <strong>$message=`'Please fill in the field :: reCaptcha'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>checkArray(</strong><em>mixed/array</em> <strong>$data</strong>)</strong> : <em>bool</em> |
| public | <strong>csrf(</strong><em>array</em> <strong>$csrfSession</strong>, <em>string</em> <strong>$message=`'csrf'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>email(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Email is invalid'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>equals(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Values ​​do not match'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>getAlerts(</strong><em>mixed</em> <strong>$data</strong>, <em>mixed</em> <strong>$excludedKeys</strong>)</strong> : <em>mixed</em> |
| public | <strong>getChecked(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>array</em> |
| public | <strong>hash(</strong><em>\string</em> <strong>$salt=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>integer(</strong><em>\string</em> <strong>$message=`'Number is required'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>maxLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Too many characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>minLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Too few characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>required(</strong><em>\string</em> <strong>$message=`'You must fill in the field'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>run()</strong> : <em>array</em> |
| public | <strong>sanitize(</strong><em>\string</em> <strong>$data</strong>, <em>null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>set(</strong><em>mixed</em> <strong>$data</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |

<hr /><a id="class-rudravalidationvalidation"></a>
### Class: \Rudra\Validation\Validation

| Visibility | Function |
|:-----------|:---------|
| public | <strong>captcha(</strong><em>bool</em> <strong>$captcha=false</strong>, <em>string</em> <strong>$secret=`''`</strong>, <em>string</em> <strong>$message=`'Please fill in the field :: reCaptcha'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>checkArray(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>bool</em> |
| public | <strong>csrf(</strong><em>array</em> <strong>$csrfSession</strong>, <em>string</em> <strong>$message=`'csrf'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>email(</strong><em>mixed</em> <strong>$verifiable</strong>, <em>\string</em> <strong>$message=`'Email is invalid'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>equals(</strong><em>mixed</em> <strong>$verifiable</strong>, <em>\string</em> <strong>$message=`'Values ​​do not match'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>getAlerts(</strong><em>mixed</em> <strong>$data</strong>, <em>mixed</em> <strong>$excludedKeys</strong>)</strong> : <em>array</em> |
| public | <strong>getChecked(</strong><em>array</em> <strong>$data</strong>, <em>array</em> <strong>$excludedKeys=array()</strong>)</strong> : <em>array</em> |
| public | <strong>hash(</strong><em>\string</em> <strong>$salt=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>integer(</strong><em>\string</em> <strong>$message=`'Number is required'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>isChecked()</strong> : <em>bool</em> |
| public | <strong>maxLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Too many characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>minLength(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$message=`'Too few characters specified'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>required(</strong><em>\string</em> <strong>$message=`'You must fill in the field'`</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>run()</strong> : <em>array</em> |
| public | <strong>sanitize(</strong><em>\string</em> <strong>$verifiable</strong>, <em>null</em> <strong>$allowableTags=null</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>set(</strong><em>mixed</em> <strong>$verifiable</strong>)</strong> : <em>[\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)</em> |
| public | <strong>setChecked(</strong><em>bool/\boolean</em> <strong>$checked</strong>)</strong> : <em>void</em> |
| public | <strong>setMessage(</strong><em>null</em> <strong>$message</strong>)</strong> : <em>void</em> |

*This class implements [\Rudra\Validation\ValidationInterface](#interface-rudravalidationvalidationinterface)*

