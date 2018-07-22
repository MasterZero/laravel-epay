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
capture transaction. Customer money -> your money
```php
use EpayApi;
// ...

$optionalParams = [
    'group'     => 'customers',
    'invoice'   => 'for some product',
]


$result = EpayApi::capture($transactionid, $amount /*, $optionalParams*/);

if ($result['captureResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}

```


### credit transaction:
Credit transaction. Part of captured (your) money -> back to customer money
```php
use EpayApi;
// ...

$optionalParams = [
    'group'     => 'customers',
    'invoice'   => 'for some product',
]

$result = EpayApi::credit($transactionid, $amount /*, $optionalParams*/);

if ($result['creditResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}

```

### delete transaction:
Delete transaction. All of non-captured (not-your) money -> back to customer money
```php
use EpayApi;
// ...

$optionalParams = [
    'group'     => 'customers',
]

$result = EpayApi::delete($transactionid /*, $optionalParams*/);

if ($result['deleteResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}

```



### authorize:
Get money for payment subscription
```php
use EpayApi;
// ...

$params = [
    'subscriptionid' => 123,
    'orderid' => 123,
    'amount' => 5000, // 50.00 * 100 
    'currency' => 208, // DKK
    'instantcapture' => 1,
    'group' => 'customer', //optional
    'description' => 'la la la', //optional
    'email' => 'test@example.com', //optional
    'sms' => 'la la la', //optional
    'ipaddress' => '255.255.255.255', //optional
]

$result = EpayApi::authorize($params);

if ($result['authorizeResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}

```



### delete subscription:
Delete subscription and stop authorize.
```php
use EpayApi;


// ...

$result = EpayApi::deletesubscription($subscriptionid);

if ($result['deletesubscriptionResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}

```


### get epay error:
Get epay error description by epayresponsecode
```php
use EpayApi;
// ...


/**
 * laguages:
 * 1 - Danish
 * 2 - English
 * 3 - Swedish
 */
$result = EpayApi::getEpayError($language, $epayresponsecode);

if ($result['getEpayErrorResult']) {
    echo "ba dum tss! the method works!";
} else {
    echo "nope. something went wrong :C";
}
```

### get pbs error:
Get pbs error description by pbsresponsecode
```php
use EpayApi;
// ...


/**
 * laguages:
 * 1 - Danish
 * 2 - English
 * 3 - Swedish
 */
$result = EpayApi::getPbsError($language, $pbsresponsecode);

if ($result['getPbsErrorResult']) {
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


$api->authorize($params);

```