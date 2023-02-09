<?php

Route::any('/', function() {
    return redirect()->route('frontend.home');
});
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    // Route::get('/', 'HomeController@index')->name('home');
    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::post('/', 'HomeController@dashboard');


    Route::get('/god-route', 'HomeController@god');

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

    //Buku
    Route::delete('buku/destroy', 'BukuController@massDestroy')->name('buku.massDestroy');
    Route::post('buku/media', 'BukuController@storeMedia')->name('buku.storeMedia');
    Route::post('buku/ckmedia', 'BukuController@storeCKEditorImages')->name('buku.storeCKEditorImages');
    Route::post('buku/import', 'BukuController@import')->name('buku.import');
    Route::resource('buku', 'BukuController');

    //Bahan
    Route::delete('bahan/destroy', 'BahanController@massDestroy')->name('bahan.massDestroy');
    Route::post('bahan/media', 'BahanController@storeMedia')->name('bahan.storeMedia');
    Route::post('bahan/ckmedia', 'BahanController@storeCKEditorImages')->name('bahan.storeCKEditorImages');
    Route::post('bahan/import', 'BahanController@import')->name('bahan.import');
    Route::resource('bahan', 'BahanController');

    // Salesperson
    Route::delete('salespeople/destroy', 'SalespersonController@massDestroy')->name('salespeople.massDestroy');
    Route::post('salespeople/media', 'SalespersonController@storeMedia')->name('salespeople.storeMedia');
    Route::post('salespeople/ckmedia', 'SalespersonController@storeCKEditorImages')->name('salespeople.storeCKEditorImages');
    Route::post('salespeople/parse-csv-import', 'SalespersonController@parseCsvImport')->name('salespeople.parseCsvImport');
    Route::post('salespeople/process-csv-import', 'SalespersonController@processCsvImport')->name('salespeople.processCsvImport');
    Route::post('salespeople/import', 'SalespersonController@import')->name('salespeople.import');
    Route::get('salespeople/select', 'SalespersonController@select')->name('salespeople.select');
    Route::resource('salespeople', 'SalespersonController');

    // Order
    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::get('orders/estimasi/{id}', 'OrderController@print_estimasi')->name('orders.estimasi');
    Route::get('orders/saldo/{id}', 'OrderController@print_saldo')->name('orders.saldo');
    Route::get('orders/saldo_rekap/{id}', 'OrderController@print_saldo_rekap')->name('orders.saldo_rekap');
    Route::post('orders/change-price', 'OrderController@change_price')->name('orders.change_price');
    Route::post('orders/change-price-single', 'OrderController@change_price_single')->name('orders.change_price_single');
    Route::post('orders/change-price-faktur', 'OrderController@ubahHargaFaktur')->name('orders.ubahHargaFaktur');
    Route::resource('orders', 'OrderController');

    // Order Detail
    Route::resource('order-details', 'OrderDetailController', ['except' => ['edit', 'update', 'show', 'destroy']]);

    // Invoice
    Route::delete('invoices/destroy', 'InvoiceController@massDestroy')->name('invoices.massDestroy');
    Route::get('invoices/retur', 'InvoiceController@fakturRetur')->name('invoices.retur');
    Route::get('invoices/retur/{invoice}/edit', 'InvoiceController@editRetur')->name('invoices.returedit');
    Route::put('invoices/retur/{invoice}', 'InvoiceController@updateRetur')->name('invoices.returupdate');
    Route::post('invoices/retursave', 'InvoiceController@fakturReturSave')->name('invoices.saveretur');
    Route::post('invoices/delete', 'InvoiceController@delete')->name('invoices.delete');
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
    Route::get('pembayarans/general', 'PembayaranController@general')->name('pembayarans.general');
    Route::get('pembayarans/export', 'PembayaranController@rekapSaldoExport')->name('pembayarans.export');
    Route::post('pembayarans/general', 'PembayaranController@generalSave')->name('pembayarans.general.save');
    Route::get('pembayarans/ajax/getTagihan', 'PembayaranController@getTagihan')->name('pembayarans.ajax.tagihan');
    Route::resource('pembayarans', 'PembayaranController');

    // Laporan Pemesanan
    Route::get('report/orders', 'ReportController@orders')->name('report.orders');
    Route::post('report/orders', 'ReportController@orders');

    // Laporan Pengiriman
    Route::get('report/invoices', 'ReportController@invoices')->name('report.invoices');
    Route::post('report/invoices', 'ReportController@invoices');

    // Laporan Pembayaran
    Route::get('report/payment', 'ReportController@payment')->name('report.payment');
    Route::post('report/payment', 'ReportController@payment');

    // Laporan Penerimaan
    Route::get('report/realisasis', 'ReportController@realisasis')->name('report.realisasis');
    Route::post('report/realisasis', 'ReportController@realisasis');

    // Stock Opname
    Route::delete('stock-opnames/destroy', 'StockOpnameController@massDestroy')->name('stock-opnames.massDestroy');
    Route::get('stock-opnames/detail', 'StockOpnameController@stockDetail')->name('stock-opnames.detail');
    Route::get('stock-opnames/export', 'StockOpnameController@stockExport')->name('stock-opnames.export');
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
    Route::get('production-orders/dashboard', 'ProductionOrderController@dashboard')->name('production-orders.dashboard');
    Route::resource('production-orders', 'ProductionOrderController');

    // Finishing Order
    Route::delete('finishing-orders/destroy', 'FinishingOrderController@massDestroy')->name('finishing-orders.massDestroy');
    Route::post('finishing-orders/parse-csv-import', 'FinishingOrderController@parseCsvImport')->name('finishing-orders.parseCsvImport');
    Route::post('finishing-orders/process-csv-import', 'FinishingOrderController@processCsvImport')->name('finishing-orders.processCsvImport');
    Route::get('finishing-orders/dashboard', 'FinishingOrderController@dashboard')->name('finishing-orders.dashboard');
    Route::resource('finishing-orders', 'FinishingOrderController');

    // Finishing Order Detail
    Route::delete('finishing-order-details/destroy', 'FinishingOrderDetailController@massDestroy')->name('finishing-order-details.massDestroy');
    Route::resource('finishing-order-details', 'FinishingOrderDetailController');

    // Custom Price
    Route::delete('custom-prices/destroy', 'CustomPriceController@massDestroy')->name('custom-prices.massDestroy');
    Route::post('custom-prices/parse-csv-import', 'CustomPriceController@parseCsvImport')->name('custom-prices.parseCsvImport');
    Route::post('custom-prices/process-csv-import', 'CustomPriceController@processCsvImport')->name('custom-prices.processCsvImport');
    Route::get('custom-prices/select', 'CustomPriceController@select')->name('custom-prices.select');
    Route::post('custom-prices/import', 'CustomPriceController@import')->name('custom-prices.import');
    Route::resource('custom-prices', 'CustomPriceController');

    // Semester
    Route::delete('semesters/destroy', 'SemesterController@massDestroy')->name('semesters.massDestroy');
    Route::resource('semesters', 'SemesterController');

    // Price
    Route::delete('prices/destroy', 'PriceController@massDestroy')->name('prices.massDestroy');
    Route::resource('prices', 'PriceController');

    // Price Detail
    Route::delete('price-details/destroy', 'PriceDetailController@massDestroy')->name('price-details.massDestroy');
    Route::resource('price-details', 'PriceDetailController');

    // Realisasi
    Route::delete('realisasis/destroy', 'RealisasiController@massDestroy')->name('realisasis.massDestroy');
    Route::post('realisasis/parse-csv-import', 'RealisasiController@parseCsvImport')->name('realisasis.parseCsvImport');
    Route::post('realisasis/process-csv-import', 'RealisasiController@processCsvImport')->name('realisasis.processCsvImport');
    Route::post('realisasis/paid', 'RealisasiController@setPaid')->name('realisasis.paid');
    Route::resource('realisasis', 'RealisasiController');

    // Preorder
    Route::delete('preorders/destroy', 'PreorderController@massDestroy')->name('preorders.massDestroy');
    Route::resource('preorders', 'PreorderController');

    // Preorder Detail
    Route::delete('preorder-details/destroy', 'PreorderDetailController@massDestroy')->name('preorder-details.massDestroy');
    Route::resource('preorder-details', 'PreorderDetailController');

    // Summary Order
    Route::get('summary-orders/synchronize', 'SummaryOrderController@synchronizeOrder')->name('summary-order.synchronize');
    Route::resource('summary-orders', 'SummaryOrderController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // History Production
    Route::delete('history-productions/destroy', 'HistoryProductionController@massDestroy')->name('history-productions.massDestroy');
    Route::resource('history-productions', 'HistoryProductionController', ['except' => ['create', 'store', 'edit', 'update']]);

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
    Route::get('production-orders/{id}/process', 'ProductionOrderController@process')->name('production-orders.process');
    Route::post('production-orders/{id}/process', 'ProductionOrderController@processSubmit');

    // Production Order
    Route::delete('finishing-orders/destroy', 'FinishingOrderController@massDestroy')->name('finishing-orders.massDestroy');
    Route::resource('finishing-orders', 'FinishingOrderController');

    // Production Order Detail
    Route::delete('finishing-order-details/destroy', 'FinishingOrderDetailController@massDestroy')->name('finishing-order-details.massDestroy');
    Route::resource('finishing-order-details', 'FinishingOrderDetailController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
