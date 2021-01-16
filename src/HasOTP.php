<?php

namespace GraxMonzo\OneTimePassword;

use Illuminate\Database\Eloquent\Model;

trait HasOTP
{
    protected int $digits = 6;

    protected OTPOptions $otpOptions;

    abstract public function getOTPOptions(): OTPOptions;

    protected static function bootHasOTP()
    {
        static::creating(function (Model $model) {
            $model->generateOTPOnCreate();
        });
    }

    protected function generateOTPOnCreate()
    {
        $this->otpOptions = $this->getOTPOptions();

        if (!$this->otpOptions->generateOTPOnCreate) {
            return;
        }

        $this->addSecret();
    }

    protected function getOTPRandomSecret($length = 20)
    {
        return Base32::random($length);
    }

    protected function addSecret()
    {
        $otpField = $this->otpOptions->otpField;

        $this->$otpField = $this->getOTPRandomSecret();
    }

    public function authenticate($code, $options = [])
    {
        $this->otpOptions = $this->getOTPOptions();
        $otpField = $this->otpOptions->otpField;
        $totp = new TOTP($this->$otpField, ['digits' => $this->digits]);
        if ($drift = $options['drift']) {
            return $totp->verify($code, $drift);
        } else {
            return $totp->verify($code);
        }
    }

    public function otpCode($options = [])
    {
        $this->otpOptions = $this->getOTPOptions();
        $otpField = $this->otpOptions->otpField;
        if (is_array($options)) {
            $time = $options['time'] ?? time();
        } else {
            $time = $options;
        }
        return (new TOTP($this->$otpField, ['digits' => $this->digits]))->at($time);
    }
}
