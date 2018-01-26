<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\User;

class FileHandler
{
    private static $FileUrl;
    private static $FileKey;

    public function FileExist($url)
    {
        self::$FileUrl = $url;

        if(!empty($url))
        {
            return file_exists(self::$FileUrl);
        }else{
            return false;
        }
    }

    public function photo($path)
    {
        if(self::FileExist($path))
        {
            // Now just display the image
            header('Content-type: image/jpeg');
            echo file_get_contents($path);
        }else{
            // Means there is no file
            return false;
        }
    }
}