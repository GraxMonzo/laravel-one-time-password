<?php

namespace GraxMonzo\OneTimePassword;

class OTPOptions
{
    public string $otpSecret = 'otp_secret';

    public string $otpCounter = 'otp_counter';

    public int $digits = 6;

    public static function create(): self
    {
        return new static();
    }

    public function saveToFields(string $secretField, string $counterField): self
    {
        $this->otpSecret = $secretField;
        $this->otpCounter = $counterField;

        return $this;
    }

    public function digitsCount(int $digits): self
    {
        $this->digits = $digits;

        return $this;
    }
}
