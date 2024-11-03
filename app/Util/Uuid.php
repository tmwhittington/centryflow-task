<?php

namespace App\Util;

use Random\RandomException;

class Uuid
{
    /**
     * @throws RandomException
     */
    public static function generate($prefix = null, $bytes = 8): string {
        $random =  bin2hex(random_bytes($bytes));
        return $prefix != null ? sprintf('%s_%s', $prefix, $random) : $random;
    }
}