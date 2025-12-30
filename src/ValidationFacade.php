<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
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
