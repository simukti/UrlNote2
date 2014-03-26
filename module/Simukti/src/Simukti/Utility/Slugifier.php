<?php
/**
 * Simukti\Utility\Slugifier
 *
 * Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Utility;

class Slugifier
{
    public static function create($string)
    {
        /**
         * https://github.com/DASPRiD/Bacon/blob/master/src/Bacon/Text/Slugifier/Slugifier.php#L58
         */
        $string = strtolower($string);
        $string = str_replace("'", '', $string);
        $string = preg_replace('([^a-zA-Z0-9_-]+)', '-', $string);
        $string = preg_replace('(-{2,})', '-', $string);
        $string = trim($string, '-');
        
        return $string;
    }
}
