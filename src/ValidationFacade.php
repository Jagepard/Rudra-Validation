<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

use Rudra\Container\Traits\FacadeTrait;

/**
 * @method static ValidationInterface set($data)
 * @method static ValidationInterface sanitize(string $data, $allowableTags = null)
 * @method static ValidationInterface email($data, string $message = 'Email is invalid')
 * @method static ValidationInterface captcha($captcha = false, $secret = '', $message = 'Please fill in the field :: reCaptcha')
 * @method static bool approve(array $data)
 * @method static array getValidated(array $data, array $excludedKeys = [])
 * @method static array getAlerts(array $data, array $excludedKeys = [])
 *
 * @see ValidationFacade
 */
final class ValidationFacade
{
    use FacadeTrait;
}
