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
 * Entry-point methods for the fluent validation chain.
 * Methods like required(), min(), max(), etc. are called on the instance returned by set()/sanitize()/email().
 *
 * @method static bool approve(array $data)
 * @method static array getValidated(array $data, array $excludedKeys = [])
 * @method static array getErrors(array $data, array $excludedKeys = [])
 * @method static void setAliases(array $aliases)
 * @method static ValidationInterface set(mixed $verifiable)
 * @method static ValidationInterface sanitize(string $verifiable, array|string|null $allowableTags = null)
 * @method static ValidationInterface email(string $verifiable, string $message = 'Invalid email address')
 *
 * @see Validation
 */
final class ValidationFacade
{
    use FacadeTrait;
}
