<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        /*
         * Zed's Service Providers...
         */
        //App\Models\ModelServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | ZedBot
    |--------------------------------------------------------------------------
    |
    | ZedBot-specific configuration variables
    |
    */

    #Self
    'ZED_SELF_CUSTOMER_IDENTIFIER' => env('ZED_SELF_CUSTOMER_IDENTIFIER'),
    'ZED_SELF_ENM_ACCOUNT_IDENTIFIER' => env('ZED_SELF_ENM_ACCOUNT_IDENTIFIER'),
    'ZED_SELF_PHONE_NUMBER' => env('ZED_SELF_PHONE_NUMBER'),
    'ZED_SELF_EMAIL_ADDRESS' => env('ZED_SELF_EMAIL_ADDRESS'),

    #CSVs
    'ZED_CUSTOMER_RECORDS_CSV_HEADERS' => (array) explode(',', env('ZED_CUSTOMER_RECORDS_CSV_HEADERS')),

    # Connections
    'ZED_CONNECT_SINGLE_TIMEOUT' => (int) env('ZED_CONNECT_SINGLE_TIMEOUT'),
    'ZED_CONNECT_RETRY' => (int) env('ZED_CONNECT_RETRY'),
    'ZED_CONNECT_ABSOLUTE_TIMEOUT' => (int) env('ZED_CONNECT_ABSOLUTE_TIMEOUT'),

    # Payment NETWORKs
    'ZED_NETWORK_LIST' => (array) explode(',', env('ZED_NETWORK_LIST')),

    # APIs
    'ZED_EXCHANGE_API_LIST' => (array) explode(',', env('ZED_EXCHANGE_API_LIST')),
    'ZED_MARKET_API_LIST' => (array) explode(',', env('ZED_MARKET_API_LIST')),
    'ZED_PAYMENT_API_LIST' => (array) explode(',', env('ZED_PAYMENT_API_LIST')),
    'ZED_APIS_THAT_USE_POST_REQUESTS_FOR_FETCHING' => (array) explode(',', env('ZED_APIS_THAT_USE_POST_REQUESTS_FOR_FETCHING')),

    # TRS
    'ZED_TRS0_DOMAIN' => (string) env('ZED_TRS0_DOMAIN'),
    'ZED_TRS0_PATH' => (string) env('ZED_TRS0_PATH'),
    'ZED_TRS0_ADDRESS_ENDPOINT' => (string) env('ZED_TRS0_ADDRESS_ENDPOINT'),
    'ZED_TRS0_TRANSACTIONS_ENDPOINT' => (string) env('ZED_TRS0_TRANSACTIONS_ENDPOINT'),

    # MMP
    'ZED_MMP0_DOMAIN' => (string) env('ZED_MMP0_DOMAIN'),
    'ZED_MMP0_PATH' => (string) env('ZED_MMP0_PATH'),
    'ZED_MMP0_ADDRESS_ENDPOINT' => (string) env('ZED_MMP0_ADDRESS_ENDPOINT'),
    'ZED_MMP0_ADDRESS_TRANSACTIONS_ENDPOINT_SUFFIX' => (string) env('ZED_MMP0_ADDRESS_TRANSACTIONS_ENDPOINT_SUFFIX'),

    # LCS
    'ZED_LCS0_USERNAME' => (string) env('ZED_LCS0_USERNAME'),
    'ZED_LCS0_DOMAIN' => (string) env('ZED_LCS0_DOMAIN'),
    'ZED_LCS0_PATH' => (string) env('ZED_LCS0_PATH'),
    'ZED_LCS0_WALLETS_ENDPOINT' => (string) env('ZED_LCS0_WALLETS_ENDPOINT'),

    # ENM
    'ZED_ENM0_ACCOUNT_NAME' => (string) env('ZED_ENM0_ACCOUNT_NAME'),
    'ZED_ENM0_ACCOUNT_ERN' => (string) env('ZED_ENM0_ACCOUNT_ERN'),
    'ZED_ENM0_ACCOUNT_CODE' => (string) env('ZED_ENM0_ACCOUNT_CODE'),
    'ZED_ENM0_DOMAIN' => (string) env('ZED_ENM0_DOMAIN'),
    'ZED_ENM0_PATH' => (string) env('ZED_ENM0_PATH'),
    'ZED_ENM0_TRANSACTIONS_ENDPOINT' => (string) env('ZED_ENM0_TRANSACTIONS_ENDPOINT'),
    'ZED_ENM0_TRANSACTIONS_BATCH_ENDPOINT' => (string) env('ZED_ENM0_TRANSACTIONS_BATCH_ENDPOINT'),
    'ZED_ENM0_BENEFICIARIES_ENDPOINT' => (string) env('ZED_ENM0_BENEFICIARIES_ENDPOINT'),

];
