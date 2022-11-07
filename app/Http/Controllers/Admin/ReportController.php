<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ReportOrdersExport;
use App\Exports\Admin\ReportInvoicesExport;
use App\Exports\Admin\ReportPembayaransExport;
use App\Exports\Admin\ReportRealisasisExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Pembayaran;
use App\Models\ProductionOrder;
use App\Models\Productionperson;
use App\Models\Realisasi;
use App\Models\Salesperson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function orders(Request $request)
    {
        $salespersons = Salesperson::get();

        $ordersQuery = Order::query()->with([
            'order_details',
            'order_details.product',
            'salesperson',
        ]);

        if ($request->has('date') && $request->date && $dates = explode(' - ', $request->date)) {
            $start = Date::parse($dates[0])->startOfDay();
            $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }
        $ordersQuery->whereBetween('date', [$start, $end]);

        if ($salesperson_id = $request->salesperson_id) {
            $ordersQuery->where('salesperson_id', $salesperson_id);
        }

        $orders = $ordersQuery->orderByDesc('date')->get();

        if ($request->export === 'excel') {
            return (new ReportOrdersExport($orders))->download('report-orders.xlsx');
        }

        return view('admin.report.orders', compact('orders', 'salespersons'));
    }

    public function invoices(Request $request)
    {
        $products = Product::get();
        $orders = Order::get();
        $salespersons = Salesperson::get();
        $invoicesQuery = Invoice::query()->with([
            'order',
            'order.salesperson',
            'invoice_details',
            'invoice_details.product',
        ]);

        if ($request->has('date') && $request->date && $dates = explode(' - ', $request->date)) {
            $start = Date::parse($dates[0])->startOfDay();
            $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }
        $invoicesQuery->whereBetween('date', [$start, $end]);

        if ($order_id = $request->order_id) {
            $invoicesQuery->where('order_id', $order_id);
        }

        if ($salesperson_id = $request->salesperson_id) {
            $invoicesQuery->whereHas('order', function($query) use ($salesperson_id) {
                $query->where('salesperson_id', $salesperson_id);
            });
        }

        if ($product_id = $request->product_id) {
            $invoicesQuery->whereHas('invoice_details', function($query) use ($product_id) {
                $query->where('product_id', $product_id);
            });

            $invoicesQuery->with([
                'invoice_details' => function($query) use ($product_id) {
                    $query->where('product_id', $product_id);
                },
            ]);
        }

        $invoices = $invoicesQuery->orderByDesc('date')->get();

        if ($request->export === 'excel') {
            return (new ReportInvoicesExport($invoices))->download('report-invoice.xlsx');
        }

        return view('admin.report.invoices', compact('orders', 'salespersons', 'invoices', 'products'));
    }

    public function payment(Request $request)
    {
        abort_if(Gate::denies('invoice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::get();
        $orders = Order::get();
        $salespersons = Salesperson::get();
        $pembayaransQuery = Pembayaran::query()->with([
            'order',
            'order.salesperson',
            'tagihan'
        ]);

        if ($request->has('date') && $request->date && $dates = explode(' - ', $request->date)) {
            $start = Date::parse($dates[0])->startOfDay();
            $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }
        $pembayaransQuery->whereBetween('tanggal', [$start, $end]);

        if ($order_id = $request->order_id) {
            $pembayaransQuery->where('order_id', $order_id);
        }

        if ($salesperson_id = $request->salesperson_id) {
            $pembayaransQuery->whereHas('order', function($query) use ($salesperson_id) {
                $query->where('salesperson_id', $salesperson_id);
            });
        }

        $pembayarans = $pembayaransQuery->orderByDesc('tanggal')->get();

        if ($request->export === 'excel') {
            return (new ReportPembayaransExport($pembayarans))->download('report-pembayaran.xlsx');
        }

        return view('admin.report.payment', compact('orders', 'salespersons', 'pembayarans'));
    }

    public function realisasis(Request $request)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::get();
        $productionOrders = ProductionOrder::get();
        $productionpeople = Productionperson::get();
        $realisasiQuery = Realisasi::query()->with([
            'production_order',
            'production_order.productionperson',
            'realisasi_details',
            'realisasi_details.product',
        ]);

        if ($request->has('date') && $request->date && $dates = explode(' - ', $request->date)) {
            $start = Date::parse($dates[0])->startOfDay();
            $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }
        $realisasiQuery->whereBetween('date', [$start, $end]);

        if ($production_order_id = $request->production_order_id) {
            $realisasiQuery->where('production_order_id', $production_order_id);
        }

        if ($productionperson_id = $request->productionperson_id) {
            $realisasiQuery->whereHas('production_order', function($query) use ($productionperson_id) {
                $query->where('productionperson_id', $productionperson_id);
            });
        }

        if ($product_id = $request->product_id) {
            $realisasiQuery->whereHas('realisasi_details', function($query) use ($product_id) {
                $query->where('product_id', $product_id);
            });

            $realisasiQuery->with([
                'realisasi_details' => function($query) use ($product_id) {
                    $query->where('product_id', $product_id);
                },
            ]);
        }

        $realisasis = $realisasiQuery->orderByDesc('date')->get();

        if ($request->export === 'excel') {
            return (new ReportRealisasisExport($realisasis))->download('report-production-order.xlsx');
        }

        return view('admin.report.realisasis', compact('productionOrders', 'productionpeople', 'realisasis', 'products'));
    }
}
