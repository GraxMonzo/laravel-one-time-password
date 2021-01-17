<?php

namespace GraxMonzo\OneTimePassword\Tests;

use GraxMonzo\OneTimePassword\HasOTP;
use GraxMonzo\OneTimePassword\OTPOptions;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasOTP;

    protected $table = 'test_models';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Get the options for generating the OTP.
     */
    public function otpOptions(): OTPOptions
    {
        return $this->otpOptions ?? $this->getDefaultOtpOptions();
    }

    /**
     * Set the options for generating the OTP.
     */
    public function setOtpOptions(OTPOptions $otpOptions): self
    {
        $this->otpOptions = $otpOptions;

        return $this;
    }

    /**
     * Get the default OTP options used in the tests.
     */
    public function getDefaultOtpOptions(): OTPOptions
    {
        return OTPOptions::create()
            ->fieldsToSave('otp_secret', 'otp_counter');
    }
}
