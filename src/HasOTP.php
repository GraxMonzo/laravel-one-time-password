<?php

namespace GraxMonzo\OneTimePassword;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OTPHP\HOTP;

trait HasOTP
{
    protected OTPOptions $otpOptions;

    abstract public function otpOptions(): OTPOptions;

    protected static function bootHasOTP(): void
    {
        static::creating(function (Model $model) {
            $model->setupOtp();
        });
    }

    protected function setupOtp(): void
    {
        $this->otpOptions = $this->otpOptions();
        extract(get_object_vars($this->otpOptions));

        $this->$otpSecret = $this->getOTPRandomSecret();
        $this->$otpCounter = 0;
    }

    protected function getOTPRandomSecret($length = 20): string
    {
        return Base32::random($length);
    }

    public function otp(): ?string
    {
        $this->otpOptions = $this->otpOptions();
        extract(get_object_vars($this->otpOptions));

        if (! $this->$otpSecret) {
            return null;
        }

        $hotp = HOTP::create($this->$otpSecret, 0, 'sha1', $digits);

        $this->$otpCounter = $this->$otpCounter + 1;
        return $hotp->at($this->$otpCounter);
    }

    public function verify(string $otp): ?bool
    {
        $this->otpOptions = $this->otpOptions();
        extract(get_object_vars($this->otpOptions));

        if (! $this->$otpSecret) {
            return null;
        }

        $hotp = HOTP::create($this->$otpSecret, 0, 'sha1', $digits);

        $otpStatus = $hotp->verify($otp, $this->$otpCounter);
        $this->$otpCounter++;
        return $otpStatus;
    }
}
