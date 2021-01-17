<?php

namespace GraxMonzo\OneTimePassword;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function otp(): ?int
    {
        $this->otpOptions = $this->otpOptions();
        extract(get_object_vars($this->otpOptions));

        if (! $this->$otpSecret) {
            return null;
        }

        $hotp = new HOTP($this->$otpSecret, compact('digits'));

        DB::beginTransaction();

        try {
            $this->$otpCounter = $this->$otpCounter + 1;
            DB::commit();
            return $hotp->at($this->$otpCounter);
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function verify($otp): ?bool
    {
        $this->otpOptions = $this->otpOptions();
        extract(get_object_vars($this->otpOptions));

        if (! $this->$otpSecret) {
            return null;
        }

        $hotp = new HOTP($this->$otpSecret, compact('digits'));

        DB::beginTransaction();

        try {
            $otpStatus = $hotp->verify($otp, $this->$otpCounter);
            $this->$otpCounter = $this->$otpCounter + 1;
            DB::commit();
            return $otpStatus;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }
}
