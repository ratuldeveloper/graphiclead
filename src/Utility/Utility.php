<?php
declare(strict_types=1);
namespace App\Utility;

class Utility {

    public static function generateRanmdomUniqueId($randomBitSize = 20) {
        $generatedNumberArray = [];
        $dividedNumbers = self::_numberDivider($randomBitSize,5);
        foreach($dividedNumbers as $bitlength) {
            $bytes = random_bytes($bitlength);
            $generatedNumberArray[] = bin2hex($bytes);

        }
        return implode('-', $generatedNumberArray);
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