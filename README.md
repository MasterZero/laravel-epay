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