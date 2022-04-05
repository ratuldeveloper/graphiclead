<?php
declare(strict_types=1);
namespace App\Utility;

use Cake\View\Helper\UrlHelper;
use Exception;
use finfo;

class Utility {

    private static $_supportedImageType = [
        'image/png' => 'png',
        'image/jpg' => 'jpg',
        'image/jpeg' => 'jpeg'
    ];
    public static function generateRanmdomUniqueId($randomBitSize = 20) {
        $generatedNumberArray = [];
        $dividedNumbers = self::_numberDivider($randomBitSize,5);
        foreach($dividedNumbers as $bitlength) {
            $bytes = random_bytes($bitlength);
            $generatedNumberArray[] = bin2hex($bytes);

        }
        return implode('-', $generatedNumberArray);
    }
    public static function createImageFromUrl($url,$uuid) {
        if(!filter_var($url,FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid url");
        }
        $imageType = self::getUrlMimeType($url);
        //return $imageType;
        if(!array_key_exists($imageType,self::$_supportedImageType)) {
            throw new Exception("Please use only png and jpg image");
        }
        $imageExtension = self::$_supportedImageType[$imageType];
        $imagePath = 'img'. DS . 'qrimage'.DS.$uuid.'.'.$imageExtension;
        if(!copy($url, WWW_ROOT.$imagePath)) {
            return false;

        }
        return [
            'file_name' => $uuid.'.'.$imageExtension,
            'content_type' => $imageType
        ];
    }
    private static function getUrlMimeType($url)
    {
        $buffer = file_get_contents($url);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($buffer);
    }
    
    private static function _numberDivider($max,$count) {
        $numbers = [];
        for ($i = 1; $i < $count; $i++) {
            $random = mt_rand(3, intval($max/($count - $i)));
            $max -= $random;
            if($max > 0) {
                $numbers[] = $max;
            }
        } 
        return $numbers;

    }
    
}

?>