# laravel-one-time-password

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graxmonzo/laravel-one-time-password.svg?style=flat-square)](https://packagist.org/packages/graxmonzo/laravel-one-time-password)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/graxmonzo/laravel-one-time-password/run-tests?label=tests)](https://github.com/graxmonzo/laravel-one-time-password/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/graxmonzo/laravel-one-time-password.svg?style=flat-square)](https://packagist.org/packages/graxmonzo/laravel-one-time-password)

Laravel implementation of Rails's [active_model_otp](https://github.com/heapsource/active_model_otp/) package.

This package provides a trait that will generate a one time password every 30 seconds.

```php
$user->otpCode() // => 324650
$user->authenticate(324650) // => true
sleep(30)
$user->authenticate(324650) // => false
```

## Installation

You can install the package via composer:

```bash
composer require graxmonzo/laravel-one-time-password
```

## Usage

Your Eloquent models should use the `GraxMonzo\OneTimePassWord\HasOTP` trait and the `GraxMonzo\OneTimePassWord\OTPOptions` class.

The trait contains an abstract method `getOTPOptions()` that you must implement yourself.

Your models' migrations should have a field to save the generated OTP secret to.

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
    public function getOTPOptions() : OTPOptions
    {
        return OTPOptions::create()
            ->saveOTPTo('otp_secret_key');
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
            $table->string('otp_secret_key'); // Field name same as your `saveOTPTo`
            $table->timestamps();
        });
    }
}
```

### Getting current code

```php
$model = new YourEloquentModel();

$model->otpCode(); # => 186522
sleep(30);
$code = $model->otpCode(); # => 850738
```

### Authenticating using a code

```php
$model->authenticate($code); # => true
sleep(30);
$model->authenticate($code); # => false
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
