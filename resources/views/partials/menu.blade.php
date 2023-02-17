<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/audit-logs*") ? "menu-open" : "" }} {{ request()->is("admin/user-alerts*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }} {{ request()->is("admin/audit-logs*") ? "active" : "" }} {{ request()->is("admin/user-alerts*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                            {{-- @can('user_alert_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.user-alerts.index") }}" class="nav-link {{ request()->is("admin/user-alerts") || request()->is("admin/user-alerts/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-bell">

                                        </i>
                                        <p>
                                            {{ trans('cruds.userAlert.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan --}}
                        </ul>
                    </li>
                @endcan
                @can('master_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/units*") ? "menu-open" : "" }} {{ request()->is("admin/brands*") ? "menu-open" : "" }} {{ request()->is("admin/cities*") ? "menu-open" : "" }} {{ request()->is("admin/categories*") ? "menu-open" : "" }} {{ request()->is("admin/custom-prices*") ? "menu-open" : "" }} {{ request()->is("admin/semesters*") ? "menu-open" : "" }} {{ request()->is("admin/prices*") ? "menu-open" : "" }} {{ request()->is("admin/price-details*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/units*") ? "active" : "" }} {{ request()->is("admin/brands*") ? "active" : "" }} {{ request()->is("admin/cities*") ? "active" : "" }} {{ request()->is("admin/categories*") ? "active" : "" }} {{ request()->is("admin/custom-prices*") ? "active" : "" }} {{ request()->is("admin/semesters*") ? "active" : "" }} {{ request()->is("admin/prices*") ? "active" : "" }} {{ request()->is("admin/price-details*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.master.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('semester_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.semesters.index") }}" class="nav-link {{ request()->is("admin/semesters") || request()->is("admin/semesters/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon far fa-clock">

                                        </i>
                                        <p>
                                            {{ trans('cruds.semester.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('unit_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.units.index") }}" class="nav-link {{ request()->is("admin/units") || request()->is("admin/units/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-puzzle-piece">

                                        </i>
                                        <p>
                                            {{ trans('cruds.unit.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('brand_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.brands.index") }}" class="nav-link {{ request()->is("admin/brands") || request()->is("admin/brands/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.brand.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('city_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.cities.index") }}" class="nav-link {{ request()->is("admin/cities") || request()->is("admin/cities/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-map-marker-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.city.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('category_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon far fa-list-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.category.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('price_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.prices.index") }}" class="nav-link {{ request()->is("admin/prices") || request()->is("admin/prices/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-dollar-sign">

                                        </i>
                                        <p>
                                            {{ trans('cruds.price.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('price_detail_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.price-details.index") }}" class="nav-link {{ request()->is("admin/price-details") || request()->is("admin/price-details/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-dollar-sign">

                                        </i>
                                        <p>
                                            {{ trans('cruds.priceDetail.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('salesperson_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.salespeople.index") }}" class="nav-link {{ request()->is("admin/salespeople") || request()->is("admin/salespeople/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-user">

                            </i>
                            <p>
                                {{ trans('cruds.salesperson.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('productionperson_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.productionpeople.index") }}" class="nav-link {{ request()->is("admin/productionpeople") || request()->is("admin/productionpeople/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-user">

                            </i>
                            <p>
                                {{ trans('cruds.productionperson.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/product/*") || request()->is("admin/buku*") || request()->is("admin/bahan*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-book">

                            </i>
                            <p>
                                {{ trans('cruds.product.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.buku.index") }}" class="nav-link {{ request()->is("admin/buku") || request()->is("admin/buku/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-book">

                                    </i>
                                    <p>
                                        Buku
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.bahan.index") }}" class="nav-link {{ request()->is("admin/bahan") || request()->is("admin/bahan/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-flask">

                                    </i>
                                    <p>
                                        Bahan
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('stock_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/stock-adjustments*") ? "menu-open" : "" }} {{ request()->is("admin/stock-movements*") ? "menu-open" : "" }} {{ request()->is("admin/stock-opnames*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-archive">

                            </i>
                            <p>
                                {{ trans('cruds.stock.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('stock_adjustment_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.stock-adjustments.index") }}" class="nav-link {{ request()->is("admin/stock-adjustments") || request()->is("admin/stock-adjustments/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-box">

                                        </i>
                                        <p>
                                            {{ trans('cruds.stockAdjustment.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('stock_movement_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.stock-movements.index") }}" class="nav-link {{ request()->is("admin/stock-movements") || request()->is("admin/stock-movements/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.stockMovement.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('stock_opname_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.stock-opnames.index") }}" class="nav-link {{ request()->is("admin/stock-opnames") || request()->is("admin/stock-opnames/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-boxes">

                                        </i>
                                        <p>
                                            {{ trans('cruds.stockOpname.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('sales_order_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/orders*") ? "menu-open" : "" }} {{ request()->is("admin/invoices*") ? "menu-open" : "" }} {{ request()->is("admin/pembayarans*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-hand-holding-usd"></i>
                            <p>
                                {{ trans('cruds.salesOrder.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('order_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-money-bill">

                                        </i>
                                        <p>
                                            {{ trans('cruds.order.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('invoice_menu_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.invoices.index") }}" class="nav-link {{ request()->is("admin/invoices") || request()->is("admin/invoices/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-file-invoice-dollar">

                                        </i>
                                        <p>
                                            {{ trans('cruds.invoice.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('pembayaran_menu_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.pembayarans.index") }}" class="nav-link {{ request()->is("admin/pembayarans") || request()->is("admin/pembayarans/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-dollar-sign">

                                        </i>
                                        <p>
                                            {{ trans('cruds.pembayaran.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('production_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/preorders*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-tags"></i>
                            <p>
                                Preorder
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('production_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.preorders.index") }}" class="nav-link {{
                                        (request()->is("admin/preorders") || request()->is("admin/preorders/*"))
                                        && !request()->is('admin/preorders/dashboard') ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-tags"></i>
                                        <p>
                                            Preorder
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('production_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/production-orders*") ? "menu-open" : "" }} {{ request()->is("admin/realisasis*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-print"></i>
                            <p>
                                Order Cetak
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('production_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.production-orders.dashboard") }}" class="nav-link {{ request()->is("admin/production-orders/dashboard") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-print"></i>
                                        <p>
                                            Rangkuman Cetak
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('production_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.production-orders.index") }}" class="nav-link {{
                                        (request()->is("admin/production-orders") || request()->is("admin/production-orders/*"))
                                        && !request()->is('admin/production-orders/dashboard') ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-print"></i>
                                        <p>
                                            Order Cetak
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('production_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/finishing-orders*") ? "menu-open" : "" }} {{ request()->is("admin/realisasis*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-print"></i>
                            <p>
                                {{ trans('cruds.productionOrder.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('production_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.finishing-orders.index") }}" class="nav-link {{ request()->is("admin/finishing-orders") || request()->is("admin/finishing-orders/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-print">

                                        </i>
                                        <p>
                                            {{ trans('cruds.productionOrder.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('production_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.realisasis.index") }}" class="nav-link {{ request()->is("admin/realisasis") || request()->is("admin/realisasis/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-list">

                                        </i>
                                        <p>
                                            Realisasi Finishing
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('sales_order_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/report/*") ? "menu-open" : "" }} {{ request()->is("admin/report/*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-file"></i>
                            <p>
                                Laporan
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.report.orders") }}" class="nav-link {{ request()->is("admin/report/orders") || request()->is("admin/report/orders/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-print">

                                    </i>
                                    <p>
                                        History Pemesanan
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.report.invoices") }}" class="nav-link {{ request()->is("admin/report/invoices") || request()->is("admin/report/invoices/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-print">

                                    </i>
                                    <p>
                                        History Pengiriman
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.report.payment") }}" class="nav-link {{ request()->is("admin/report/payment") || request()->is("admin/report/payment/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-print">

                                    </i>
                                    <p>
                                        History Pembayaran
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.report.realisasis") }}" class="nav-link {{ request()->is("admin/report/realisasis") || request()->is("admin/report/realisasis/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-print">

                                    </i>
                                    <p>
                                        History Penerimaan
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="nav-item has-treeview {{ request()->is("admin/faktur") || request()->is("admin/faktur/*") ? "menu-open" : "" }} {{ request()->is("admin/purchases") || request()->is("admin/purchases/*") ? "menu-open" : "" }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fa-fw nav-icon fas fa-file"></i>
                        <p>
                            Gudang
                            <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route("admin.purchases.index") }}" class="nav-link {{ request()->is("admin/purchases") || request()->is("admin/purchases/*") ? "active" : "" }}">
                                <i class="fa-fw nav-icon fas fa-print">

                                </i>
                                <p>
                                    Produk Masuk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.faktur.index") }}" class="nav-link {{ request()->is("admin/faktur") || request()->is("admin/faktur/*") ? "active" : "" }}">
                                <i class="fa-fw nav-icon fas fa-print">

                                </i>
                                <p>
                                    Pengiriman Produk
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.systemCalendar") }}" class="nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "active" : "" }}">
                        <i class="fas fa-fw fa-calendar nav-icon">

                        </i>
                        <p>
                            {{ trans('global.systemCalendar') }}
                        </p>
                    </a>
                </li>
                {{-- @php($unread = \App\Models\QaTopic::unreadCount())
                <li class="nav-item">
                    <a href="{{ route("admin.messenger.index") }}" class="{{ request()->is("admin/messenger") || request()->is("admin/messenger/*") ? "active" : "" }} nav-link">
                        <i class="fa-fw fa fa-envelope nav-icon">

                        </i>
                        <p>{{ trans('global.messages') }}</p>
                        @if($unread > 0)
                            <strong>( {{ $unread }} )</strong>
                        @endif

                    </a>
                </li> --}}
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
