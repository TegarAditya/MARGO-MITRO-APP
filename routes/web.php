<?php

Route::view('/', 'welcome');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Unit
    Route::delete('units/destroy', 'UnitController@massDestroy')->name('units.massDestroy');
    Route::post('units/parse-csv-import', 'UnitController@parseCsvImport')->name('units.parseCsvImport');
    Route::post('units/process-csv-import', 'UnitController@processCsvImport')->name('units.processCsvImport');
    Route::resource('units', 'UnitController');

    // Brand
    Route::delete('brands/destroy', 'BrandController@massDestroy')->name('brands.massDestroy');
    Route::post('brands/parse-csv-import', 'BrandController@parseCsvImport')->name('brands.parseCsvImport');
    Route::post('brands/process-csv-import', 'BrandController@processCsvImport')->name('brands.processCsvImport');
    Route::resource('brands', 'BrandController');

    // City
    Route::delete('cities/destroy', 'CityController@massDestroy')->name('cities.massDestroy');
    Route::post('cities/parse-csv-import', 'CityController@parseCsvImport')->name('cities.parseCsvImport');
    Route::post('cities/process-csv-import', 'CityController@processCsvImport')->name('cities.processCsvImport');
    Route::resource('cities', 'CityController');

    // Category
    Route::delete('categories/destroy', 'CategoryController@massDestroy')->name('categories.massDestroy');
    Route::post('categories/parse-csv-import', 'CategoryController@parseCsvImport')->name('categories.parseCsvImport');
    Route::post('categories/process-csv-import', 'CategoryController@processCsvImport')->name('categories.processCsvImport');
    Route::resource('categories', 'CategoryController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::post('products/parse-csv-import', 'ProductController@parseCsvImport')->name('products.parseCsvImport');
    Route::post('products/process-csv-import', 'ProductController@processCsvImport')->name('products.processCsvImport');
    Route::post('products/import', 'ProductController@import')->name('products.import');
    Route::resource('products', 'ProductController');

    // Salesperson
    Route::delete('salespeople/destroy', 'SalespersonController@massDestroy')->name('salespeople.massDestroy');
    Route::post('salespeople/media', 'SalespersonController@storeMedia')->name('salespeople.storeMedia');
    Route::post('salespeople/ckmedia', 'SalespersonController@storeCKEditorImages')->name('salespeople.storeCKEditorImages');
    Route::post('salespeople/parse-csv-import', 'SalespersonController@parseCsvImport')->name('salespeople.parseCsvImport');
    Route::post('salespeople/process-csv-import', 'SalespersonController@processCsvImport')->name('salespeople.processCsvImport');
    Route::post('salespeople/import', 'SalespersonController@import')->name('salespeople.import');
    Route::resource('salespeople', 'SalespersonController');

    // Order
    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::resource('orders', 'OrderController');

    // Order Detail
    Route::resource('order-details', 'OrderDetailController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Invoice
    Route::delete('invoices/destroy', 'InvoiceController@massDestroy')->name('invoices.massDestroy');
    Route::resource('invoices', 'InvoiceController');

    // Invoice Detail
    Route::resource('invoice-details', 'InvoiceDetailController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Stock Adjustment
    Route::delete('stock-adjustments/destroy', 'StockAdjustmentController@massDestroy')->name('stock-adjustments.massDestroy');
    Route::resource('stock-adjustments', 'StockAdjustmentController');
    Route::post('stock-adjustments/import', 'StockAdjustmentController@import')->name('stock-adjustments.import');


    // Stock Movement
    Route::resource('stock-movements', 'StockMovementController', ['only' => ['index']]);

    // Tagihan
    Route::delete('tagihans/destroy', 'TagihanController@massDestroy')->name('tagihans.massDestroy');
    Route::resource('tagihans', 'TagihanController');

    // Tagihan Movement
    Route::resource('tagihan-movements', 'TagihanMovementController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Pembayaran
    Route::delete('pembayarans/destroy', 'PembayaranController@massDestroy')->name('pembayarans.massDestroy');
    Route::resource('pembayarans', 'PembayaranController');

    // Laporan Pengiriman
    Route::get('report/invoices', 'ReportController@invoices')->name('report.invoices');
    Route::post('report/invoices', 'ReportController@invoices');

    // Laporan Pembayaran
    Route::get('report/payment', 'ReportController@payment')->name('report.payment');
    Route::post('report/payment', 'ReportController@payment');

    // Stock Opname
    Route::delete('stock-opnames/destroy', 'StockOpnameController@massDestroy')->name('stock-opnames.massDestroy');
    Route::resource('stock-opnames', 'StockOpnameController');

    // Productionperson
    Route::delete('productionpeople/destroy', 'ProductionpersonController@massDestroy')->name('productionpeople.massDestroy');
    Route::post('productionpeople/parse-csv-import', 'ProductionpersonController@parseCsvImport')->name('productionpeople.parseCsvImport');
    Route::post('productionpeople/process-csv-import', 'ProductionpersonController@processCsvImport')->name('productionpeople.processCsvImport');
    Route::post('productionpeople/import', 'ProductionpersonController@import')->name('productionpeople.import');
    Route::resource('productionpeople', 'ProductionpersonController');

    // Production Order
    Route::delete('production-orders/destroy', 'ProductionOrderController@massDestroy')->name('production-orders.massDestroy');
    Route::post('production-orders/parse-csv-import', 'ProductionOrderController@parseCsvImport')->name('production-orders.parseCsvImport');
    Route::post('production-orders/process-csv-import', 'ProductionOrderController@processCsvImport')->name('production-orders.processCsvImport');
    Route::resource('production-orders', 'ProductionOrderController');

    // Production Order Detail
    Route::delete('production-order-details/destroy', 'ProductionOrderDetailController@massDestroy')->name('production-order-details.massDestroy');
    Route::resource('production-order-details', 'ProductionOrderDetailController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Unit
    Route::delete('units/destroy', 'UnitController@massDestroy')->name('units.massDestroy');
    Route::resource('units', 'UnitController');

    // Brand
    Route::delete('brands/destroy', 'BrandController@massDestroy')->name('brands.massDestroy');
    Route::resource('brands', 'BrandController');

    // City
    Route::delete('cities/destroy', 'CityController@massDestroy')->name('cities.massDestroy');
    Route::resource('cities', 'CityController');

    // Category
    Route::delete('categories/destroy', 'CategoryController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoryController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductController');

    // Salesperson
    Route::delete('salespeople/destroy', 'SalespersonController@massDestroy')->name('salespeople.massDestroy');
    Route::post('salespeople/media', 'SalespersonController@storeMedia')->name('salespeople.storeMedia');
    Route::post('salespeople/ckmedia', 'SalespersonController@storeCKEditorImages')->name('salespeople.storeCKEditorImages');
    Route::resource('salespeople', 'SalespersonController');

    // Order
    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::resource('orders', 'OrderController');

    // Order Detail
    Route::resource('order-details', 'OrderDetailController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Invoice
    Route::delete('invoices/destroy', 'InvoiceController@massDestroy')->name('invoices.massDestroy');
    Route::resource('invoices', 'InvoiceController');

    // Invoice Detail
    Route::resource('invoice-details', 'InvoiceDetailController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Stock Adjustment
    Route::delete('stock-adjustments/destroy', 'StockAdjustmentController@massDestroy')->name('stock-adjustments.massDestroy');
    Route::resource('stock-adjustments', 'StockAdjustmentController');

    // Stock Movement
    Route::resource('stock-movements', 'StockMovementController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Tagihan
    Route::delete('tagihans/destroy', 'TagihanController@massDestroy')->name('tagihans.massDestroy');
    Route::resource('tagihans', 'TagihanController');

    // Tagihan Movement
    Route::resource('tagihan-movements', 'TagihanMovementController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Pembayaran
    Route::delete('pembayarans/destroy', 'PembayaranController@massDestroy')->name('pembayarans.massDestroy');
    Route::resource('pembayarans', 'PembayaranController');

    // Stock Opname
    Route::delete('stock-opnames/destroy', 'StockOpnameController@massDestroy')->name('stock-opnames.massDestroy');
    Route::resource('stock-opnames', 'StockOpnameController');

    // Productionperson
    Route::delete('productionpeople/destroy', 'ProductionpersonController@massDestroy')->name('productionpeople.massDestroy');
    Route::resource('productionpeople', 'ProductionpersonController');

    // Production Order
    Route::delete('production-orders/destroy', 'ProductionOrderController@massDestroy')->name('production-orders.massDestroy');
    Route::resource('production-orders', 'ProductionOrderController');

    // Production Order Detail
    Route::delete('production-order-details/destroy', 'ProductionOrderDetailController@massDestroy')->name('production-order-details.massDestroy');
    Route::resource('production-order-details', 'ProductionOrderDetailController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
