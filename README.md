# laravel-one-time-password

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graxmonzo/laravel-one-time-password.svg)](https://packagist.org/packages/graxmonzo/laravel-one-time-password)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/graxmonzo/laravel-one-time-password/Tests?label=tests)](https://github.com/graxmonzo/laravel-one-time-password/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/graxmonzo/laravel-one-time-password.svg)](https://packagist.org/packages/graxmonzo/laravel-one-time-password)

Laravel implementation of Rails's [active_model_otp](https://github.com/heapsource/active_model_otp/) package.

Simple OTP generation.

```php
$code = $user->otp(); // => "324650"
$user->verify($code); // => true
$user->verify($code); // => false
```

## Installation

You can install the package via composer:

```bash
composer require graxmonzo/laravel-one-time-password
```

## Usage

Your Eloquent models should use the `GraxMonzo\OneTimePassWord\HasOTP` trait and the `GraxMonzo\OneTimePassWord\OTPOptions` class.

The trait contains an abstract method `otpOptions()` that you must implement yourself.

Your models' migrations should have a fields to save the OTP secret and counter to.

Here's an example of how to implement the trait:

```php
namespace App;

use GraxMonzo\OneTimePassword\HasOTP;
use GraxMonzo\OneTimePassword\OTPOptions;
use Illuminate\Database\Eloquent\Model;

class YourEloquentModel extends Model
{
    use HasOTP;

    /**
     * Get the options for generating OTP.
     */
    public function otpOptions() : OTPOptions
    {
        return OTPOptions::create()
            ->fieldsToSave('otp_secret', 'otp_counter')
            ->digitsCount(6); // optional
    }
}
```

With its migration:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYourEloquentModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('your_eloquent_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('otp_secret');
            $table->integer('otp_counter');
            $table->timestamps();
        });
    }
}
```

### Get code

```php
$model = new YourEloquentModel();

$model->otp(); # => "186522"
$code = $model->otp(); # => "850738"
```

### Verify code

```php
$model->verify($code); # => true
$model->verify($code); # => false
```

### Verify code with counter adjust

```php
$model->verify($code); # => true
$model->otp_counter -= 1;
$model->verify($code); # => true
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [GraxMonzo](https://github.com/graxmonzo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
