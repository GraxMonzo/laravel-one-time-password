<?php

namespace GraxMonzo\OneTimePassword;

use Base32\Base32 as VendorBase32;

class Base32 extends VendorBase32
{
    public static function random($byteLength = 20)
    {
        $randomBytes = random_bytes($byteLength);

        return parent::encode($randomBytes);
    }

    public static function randomBase32($strLen = 32)
    {
        $byteLength = $strLen * 5 / 8;

        return self::random($byteLength);
    }
}
