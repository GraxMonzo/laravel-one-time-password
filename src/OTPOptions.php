<?php

namespace GraxMonzo\OneTimePassword;

class OTPOptions
{
    public string $otpField;

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
}
