<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

use Rudra\Container\Traits\FacadeTrait;

/**
 * @method static ValidationInterface set($data)
 * @method static ValidationInterface sanitize(string $data, $allowableTags = null)
 * @method static ValidationInterface email($data, string $message = 'Email is invalid')
 * @method static ValidationInterface captcha($captcha = false, $secret = '', $message = 'Please fill in the field :: reCaptcha')
 * @method static bool checkArray(array $data)
 * @method static array getChecked(array $data, array $excludedKeys = [])
 * @method static array getAlerts($data, $excludedKeys)
 *
 * @see ValidationFacade
 */
final class ValidationFacade
{
    use FacadeTrait;
}
