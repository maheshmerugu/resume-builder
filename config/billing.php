<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Billing / subscription enforcement
    |--------------------------------------------------------------------------
    |
    | When false, plan and subscription checks are skipped so resume creation,
    | PDF downloads, and AI writing work without an active subscription.
    | Disabled by default in local and testing environments.
    |
    */
    'enabled' => filter_var(
        env('BILLING_ENABLED', ! in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)),
        FILTER_VALIDATE_BOOLEAN
    ),
];
