<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Unit
    Route::apiResource('units', 'UnitApiController');

    // Brand
    Route::apiResource('brands', 'BrandApiController');

    // City
    Route::apiResource('cities', 'CityApiController');

    // Category
    Route::apiResource('categories', 'CategoryApiController');

    // Product
    Route::apiResource('products', 'ProductApiController');

    // Salesperson
    Route::apiResource('salespeople', 'SalespersonApiController');

    // Order
    Route::apiResource('orders', 'OrderApiController');

    // Invoice
    Route::apiResource('invoices', 'InvoiceApiController');

    // Stock Adjustment
    Route::apiResource('stock-adjustments', 'StockAdjustmentApiController');

    // Stock Movement
    Route::apiResource('stock-movements', 'StockMovementApiController', ['except' => ['show', 'update', 'destroy']]);

    // Tagihan
    Route::apiResource('tagihans', 'TagihanApiController');

    // Tagihan Movement
    Route::apiResource('tagihan-movements', 'TagihanMovementApiController', ['except' => ['store', 'update', 'destroy']]);
});
