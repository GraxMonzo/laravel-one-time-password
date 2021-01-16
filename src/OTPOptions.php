<?php

namespace GraxMonzo\OneTimePassword;

class OTPOptions
{
    public string $otpField;

    public int $digits = 6;

    public bool $generateOTPOnCreate = true;

    public static function create(): self
    {
        return new static();
    }

    public function saveOTPTo(string $fieldName): self
    {
        $this->otpField = $fieldName;

        return $this;
    }

    public function withDigits(int $digits): self
    {
        $this->digits = $digits;

        return $this;
    }
}
