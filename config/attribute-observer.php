<?php

use App\Models\Product;

return [
    /*
    |--------------------------------------------------------------------------
    | Attribute Observers
    |--------------------------------------------------------------------------
    |
    | Here you may configure all desired Models and their respective Attribute
    | Observers. For example:
    |
    | 'observers' => [
    |    \App\Models\Order::class => [
    |        \App\AttributeObservers\OrderStatusObserver::class,
    |    ],
    | ]
    |
    */

    'observers' => [
        \App\Models\Product::class => [
            \App\AttributeObservers\ProductStockObserver::class,
        ],
    ]
];
