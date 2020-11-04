<?php

namespace App\Validation;

class ValidatedRequest
{
    // validation data request
    public static function isValidatedName($data)
    {
        if ( empty($data['name']) || ctype_space($data['name']) )
        {
                return false;
        }

        return true;
    }
    

    // validation data Price more:
    public static function isValidatedPrice($data)
    {
        if ( empty($data['price']) )
        {
            return false;
        }
        return true;
    }

    // validation data checkbox more:
    public static function isValidatedCheckbox($data)
    {
        if ( empty($data['checkboxTagName']) )
        {
            return false;
        }
        return true;
    }

    // validation data Email:
    public static function isValidatedEmail($data)
    {
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        if ( empty($data['email']) ||
             preg_match($pattern, $data['email']) != 1 ||
             !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        ) {
            return false;
        }
        return true;
    }

    // validation data Password:
    public static function isValidatedPass($data)
    {
        if ( empty($data['password']) || empty($data['re_password']) || ($data['password'] != $data['re_password']) )
        {
            return false;
        }
        return true;
    }
    // validation data Password:
    public static function isValidatedPassUpdate($data)
    {
        if ( empty($data['old_password']) || empty($data['new_password']) || empty($data['re_password']) || ($data['new_password'] != $data['re_password']) )
        {
            return false;
        }
        return true;
    }

        
}