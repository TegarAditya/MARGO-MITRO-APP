<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Pembayaran;
use App\Models\Salesperson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function invoices(Request $request)
    {
        abort_if(Gate::denies('invoice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        $invoicesQuery->whereBetween('created_at', [$start, $end]);

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

        return view('admin.report.payment', compact('orders', 'salespersons', 'pembayarans'));
    }
}
