<?php

namespace App\Helpers;

class FilesHelper {

    /**
     * Create a one-of-a-kind string and return the full path with it.
     * 
     * @param string $filename
     * @return string
     */
    public static function generateUniqueFileName(string $filename) 
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueFilename = bin2hex(random_bytes(8)) . '.' . $extension;
        
        return $uniqueFilename;
    }
    
    /**
     * To compare filesizes match.
     * 
     * @param int $size_from_files_arr
     * @param int $max_size
     * @return bool
     */
    public static function fileSize(int $size_from_files_arr, int $max_size): bool
    {
        $res = ($size_from_files_arr <= $max_size) ? true : false;
          
        return $res;
    }
    
    /**
     * Upload file to server.
     * 
     * @param string $tmp_name
     * @param string $destination
     * @return bool
     */
    public static function upload(string $tmp_name, string $destination)
    {
        return move_uploaded_file($tmp_name, $destination);
    }

    
}
