# laravel ePay API
library to work with ePay payment system via API.

# Setup:
1. Use following command in your terminal to install this library. (Currently the library is in development mode):

    `composer require masterzero/epay dev-master`

2. Update the poviders in config/app.php

        'providers' => [
            // ...
            MasterZero\Epay\ApiServiceProvider::class,
        ]

3. Update the aliases in config/app.php

        'aliases' => [
            // ...
            'EpayApi' => MasterZero\Epay\Facade\Api::class,
        ]

4. Create `config/epay.php` with content:

```php

return [
    'merchantnumber'=> env('EPAY_MERCHANTNUMBER', '1337'),
    'password'=> env('EPAY_PASSWORD', '12345678'),
];

```

5. Add these params to `.env` (optional):

```sh

EPAY_MERCHANTNUMBER=1337
EPAY_PASSWORD=12345678

```

# Usage:

### capture transaction:

```php
use EpayApi;

// ...

$result = EpayApi::capture(12345678, 10000);

if ($result['captureResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}



```



# multi-merchant usage

```php

use MasterZero\Epay\Api;

// ...

$api = new Api([
    'merchantnumber' => '1337',
    'password' => '12345678',
]);


$api->gettransaction('111111111');

```