<?php

namespace GraxMonzo\OneTimePassword\Tests;

use GraxMonzo\OneTimePassword\OTPOptions;

class HasOTPTest extends TestCase
{
    /** @test */
    public function it_will_create_a_secret_when_saving_a_model()
    {
        $model = TestModel::create();

        $this->assertNotNull($model->otp_secret);
    }

    /** @test */
    public function it_can_generate_otp()
    {
        $model = TestModel::create();

        $otp = $model->otp();

        $this->assertEquals(6, strlen($otp));
        $this->assertNotEquals($otp, $model->otp());
    }

    /** @test */
    public function it_can_verify_otp()
    {
        $model = TestModel::create();

        $otp = $model->otp();
        $this->assertTrue($model->verify($otp));
        $this->assertFalse($model->verify($otp));

        $otp = $model->otp();
        $this->assertTrue($model->verify($otp));
        $model->otp_counter -= 1;
        $this->assertTrue($model->verify($otp));
    }

    /** @test */
    public function it_can_generate_otp_with_a_custom_digits()
    {
        $model = new class extends TestModel
        {
            public function otpOptions(): OTPOptions
            {
                return parent::otpOptions()->digitsCount(4);
            }
        };

        $model->save();

        $this->assertEquals(4, strlen($model->otp()));
    }
}
