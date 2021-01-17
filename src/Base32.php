<?php

namespace GraxMonzo\OneTimePassword;

use Base32\Base32 as VendorBase32;

class Base32 extends VendorBase32
{
    public static function random(int $byteLength = 20): string
    {
        $randomBytes = random_bytes($byteLength);

        return parent::encode($randomBytes);
    }
}
