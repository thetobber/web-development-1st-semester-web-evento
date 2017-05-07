<?php
namespace Evento\Models;

use Respect\Validation\Validator as Respect;

/**
 * Defines static validators for arrays and their values.
 * @link https://github.com/Respect/Validation/blob/master/docs/README.md
 */
class Validator
{
    /**
     * Static array for storing validators so they can be 
     * re-used multiple times.
     *
     * @var array
     */
    private static $validators = [];

    /**
     * Array of error messages.
     *
     * @var array
     */
    const ERRORS = [
        'signIn' => 'Invalid e-mail or password.',
        'signUp' => [
            'username' => 'Your username must be between 1 and 255 characters.',
            'email' => 'You must use a valid e-mail address.',
            'password' => 'Your password must be between 5 and 255 characters.',
            'password_confirmation' => 'You must enter the same password.'
        ]
    ];

    /**
     * Attempt to validate credentials for signing in.
     *
     * @param array $data
     * @throws NestedValidationException if assertion fails.
     * @link https://github.com/Respect/Validation/blob/master/docs/README.md#exception-types
     */
    public static function signIn(array $data)
    {
        if (!isset(static::$validators['signIn'])) {
            static::$validators['signIn'] = Respect::arrayType()
                ->key('email',
                    Respect::email()
                )
                ->key('password',
                    Respect::length(5, 255)
                );
        }

        static::$validators['signIn']->assert($data);
    }

    /**
     * Validating passsed information for creating a new user.
     *
     * @param array $data
     * @throws NestedValidationException if assertion fails.
     * @link https://github.com/Respect/Validation/blob/master/docs/README.md#exception-types
     */
    public static function signUp(array $data)
    {
        if (!isset(static::$validators['signUp'])) {
            static::$validators['signUp'] = Respect::arrayType()
                ->key('username',
                    Respect::noWhitespace()
                        ->length(1, 255)
                )
                ->key('email',
                    Respect::email()
                )
                ->key('password',
                    Respect::length(5, 255)
                )
                ->key('password_confirmation',
                    Respect::notEmpty()
                        ->equals($data['password'] ?? null)
                        ->length(5, 255)
                );
        }

        static::$validators['signUp']->assert($data);
    }
}
