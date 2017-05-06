<?php
namespace Evento\Validation;

use Respect\Validation\Validator as Respect;

/**
 * Defines static validators for arrays and their values.
 */
class Validator
{
    /**
     * Static array for storing validators so they can be 
     * re-used multiple times.
     */
    private static $validators = [];

    /**
     * Get a validator instance for checking credential 
     * contraints when a individual attempts to sign in.
     */
    public static function signIn()
    {
        if (!isset(static::$validators['signIn'])) {
            static::$validators['signIn'] = Respect::arrayType()
                ->key('email',
                    Respect::length(0, 255)
                        ->email()
                )
                ->key('password',
                    Respect::length(5, 255)
                );
        }

        return static::$validators['signIn'];
    }

    /**
     * Get a validator instance for checking credential 
     * contraints when a individual attempts to sign up.
     */
    public static function signUp()
    {
        if (!isset(static::$validators['signUp'])) {
            // Cloning the validator instance create from signIn()
            $validator = clone static::signIn()
                ->key('username',
                    Respect::noWhitespace()
                        ->length(5, 255)
                        ->alnum('-_')
                )
                ->keyValue('password_confirmation',
                    'equals',
                    'password'
                );

            static::$validators['signUp'] = $validator;
        }

        return static::$validators['signUp'];
    }
}
