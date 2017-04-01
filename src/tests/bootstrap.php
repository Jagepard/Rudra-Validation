<?php

declare(strict_types = 1);

define('BP', dirname(dirname(__DIR__)) . '/');

require_once BP . 'vendor/autoload.php';
require_once dirname(__DIR__) . '/Validation.php';

//$publicKey = '6LdY3yUTAAAAAD0omahBOZlxM92RMkIn9gEBob3W';
//
//$v = new \Rudra\Validation('6LdY3yUTAAAAAID_xxpgUjBifcP1_I8Mb4NGKdMK');
//
//$res = [
//    $v->captcha($publicKey)->v(),
//];
//
//var_dump($res);