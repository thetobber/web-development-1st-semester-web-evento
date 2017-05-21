<?php
namespace Evento\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException as NestedException;

class UserValidator
{
    protected $errorList = [
        'username' => [
            'notEmpty' => 'Username cannot be empty.',
            'noWhiteSpace' => 'Username cannot contain whitespace.',
            'alnum' => 'Username can only contain alpha numeric characters, underscores and hyphens.'
        ],
        'email' => [
            'notEmpty' => 'E-mail must not be empty.',
            'email' => 'E-mail is not valid.'
        ],
        'password' => [
            'notEmpty' => 'Password cannot be empty.',
            'length' => 'Password must be between 12 and 4096 characters.'
        ],
        'password_confirmation' => [
            'equals' => 'Must be identical to password.',
            'length' => 'Must be identical to password.'
        ]
    ];

    public function getUsernameValidator()
    {
        return Respect::notEmpty()->noWhiteSpace()->alnum('-_');
    }

    public function getEmailValidator()
    {
        return Respect::notEmpty()->email();
    }

    public function getPasswordValidator()
    {
        return Respect::notEmpty()->length(12, 4096);
    }

    public function getPasswordConfirmValidator($password)
    {
        return Respect::equals($password)->length(12, 4096);
    }

    public function validate($model, array $rules)
    {
        $errorList = [];

        foreach ($rules as $key => $rule) {
            if (!isset($model[$key])) {
                $errorList[$key] = 'Missing.';
                continue;
            }

            try {
                $rule->assert($model[$key]);
            } catch (NestedException $exception) {
                $errorList[$key] = $exception->findMessages($this->errorList[$key]);
            }
        }

        return $errorList;
    }
}