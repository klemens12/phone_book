<?php

namespace App\Helpers;

class DataValidationHelper {

    /**
     * Check password validity by rules: >=6chars, separated chars registry and contain digit.
     * 
     * @param string $password
     * @return bool
     */
    public static function password(string $password): bool
    {
       
        return strlen($password) >= 6 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/\d/', $password) &&
        !preg_match('/[^a-zA-Z0-9]/', $password);
    }
    
    /**
     * Check email validity.
     * 
     * @param string $email
     * @return bool
     */
    public static function email(string $email): bool
    {
        
        $res = filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
        
        return $res;
    }
    
    /**
     * Check login validity by rules: >=3chars<=16, chars+ digits.
     * 
     * @param string $login
     * @return bool
     */
    public static function login(string $login): bool
    {
        
        $pattern = '/^[a-zA-Z0-9]{3,16}$/';

        return (bool) preg_match($pattern, $login);
    }
    
    /**
     * Check image mimetypes.
     * 
     * @param string $type_from_files_arr
     * @return bool
     */
    public static function image(string $type_from_files_arr): bool
    {
        
        $allowed_types = ['image/png', 'image/jpeg'];
        $res = in_array($type_from_files_arr, $allowed_types) ? true : false;
        
        return $res;
    }

    /**
     * Check that the string only contains Cyrillic/Latin characters +digits and _- tabs.
     * 
     * @param string $text
     * @return bool
     */
    public static function textInput(string $text): bool
    {
        
        $pattern = '/^[\p{Cyrillic}\p{Latin}0-9\_\-\h]+$/u';
        
        return (bool) preg_match($pattern, $text); 
    }
    
    /**
     * Check UA phone format(+380000000000)
     * 
     * @param string $phone
     * @return bool
     */
    public static function uaPhone(string $phone): bool
    {
        
        $text = preg_replace('/\s+/', '', $phone);
        $pattern = '/^\+380\d{9}$/';

    
        return (bool) preg_match($pattern, $text);
    }
    
}
