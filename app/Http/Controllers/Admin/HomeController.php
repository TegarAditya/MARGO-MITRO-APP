<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salesperson;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Permission;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use NumberFormatter;
use DB;

class HomeController
{
    public function index()
    {
        $settings1 = [
            'chart_title'           => 'Jumlah Product',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Product',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'stock',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'product',
        ];

        $settings1['total_number'] = 0;
        if (class_exists($settings1['model'])) {
            $settings1['total_number'] = $settings1['model']::when(isset($settings1['filter_field']), function ($query) use ($settings1) {
                if (isset($settings1['filter_days'])) {
                    return $query->where($settings1['filter_field'], '>=',
                now()->subDays($settings1['filter_days'])->format('Y-m-d'));
                }
                if (isset($settings1['filter_period'])) {
                    switch ($settings1['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday')); break;
                case 'month': $start = date('Y-m') . '-01'; break;
                case 'year': $start = date('Y') . '-01-01'; break;
            }
                    if (isset($start)) {
                        return $query->where($settings1['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings1['aggregate_function'] ?? 'count'}($settings1['aggregate_field'] ?? '*');
        }

        $settings2 = [
            'chart_title'           => 'Jumlah Invoice',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Invoice',
            'group_by_field'        => 'date',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'nominal',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'Y-m-d',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'translation_key'       => 'invoice',
        ];

        $settings2['total_number'] = 0;
        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when(isset($settings2['filter_field']), function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where($settings2['filter_field'], '>=',
                now()->subDays($settings2['filter_days'])->format('Y-m-d'));
                }
                if (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday')); break;
                case 'month': $start = date('Y-m') . '-01'; break;
                case 'year': $start = date('Y') . '-01-01'; break;
            }
                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        }

        $settings3 = [
            'chart_title'           => 'Total Pembayaran',
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Pembayaran',
            'group_by_field'        => 'tanggal',
            'group_by_period'       => 'week',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'bayar',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'translation_key'       => 'pembayaran',
        ];

        $chart3 = new LaravelChart($settings3);

        $settings4 = [
            'chart_title'        => 'Product',
            'chart_type'         => 'bar',
            'report_type'        => 'group_by_string',
            'model'              => 'App\Models\Product',
            'group_by_field'     => 'name',
            'aggregate_function' => 'sum',
            'aggregate_field'    => 'stock',
            'filter_field'       => 'created_at',
            'column_class'       => 'col-md-12',
            'entries_number'     => '5',
            'translation_key'    => 'product',
        ];

        $chart4 = new LaravelChart($settings4);

        $settings5 = [
            'chart_title'           => 'Invoice',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Invoice',
            'group_by_field'        => 'date',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'Y-m-d',
            'column_class'          => 'col-md-12',
            'entries_number'        => '10',
            'fields'                => [
                'no_suratjalan' => '',
                'no_invoice'    => '',
                'date'          => '',
                'nominal'       => '',
            ],
            'translation_key' => 'invoice',
        ];

        $settings5['data'] = [];
        if (class_exists($settings5['model'])) {
            $settings5['data'] = $settings5['model']::latest()
                ->take($settings5['entries_number'])
                ->get();
        }

        if (!array_key_exists('fields', $settings5)) {
            $settings5['fields'] = [];
        }

        $settings6 = [
            'chart_title'           => 'Jumlah Marketing',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Salesperson',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-3',
            'entries_number'        => '5',
            'translation_key'       => 'salesperson',
        ];

        $settings6['total_number'] = 0;
        if (class_exists($settings6['model'])) {
            $settings6['total_number'] = $settings6['model']::when(isset($settings6['filter_field']), function ($query) use ($settings6) {
                if (isset($settings6['filter_days'])) {
                    return $query->where($settings6['filter_field'], '>=',
                now()->subDays($settings6['filter_days'])->format('Y-m-d'));
                }
                if (isset($settings6['filter_period'])) {
                    switch ($settings6['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday')); break;
                case 'month': $start = date('Y-m') . '-01'; break;
                case 'year': $start = date('Y') . '-01-01'; break;
            }
                    if (isset($start)) {
                        return $query->where($settings6['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings6['aggregate_function'] ?? 'count'}($settings6['aggregate_field'] ?? '*');
        }

        return view('home', compact('chart3', 'chart4', 'settings1', 'settings2', 'settings5', 'settings6'));
    }

    public function dashboard(Request $request)
    {
        if ($request->input('date')) {
            $dates = explode(' - ', $request->input('date', ' - '));
            $start_at = Date::parse($dates[0] ?: 'first day of this month')->startOf('day');
            $end_at = Date::parse($dates[1] ?: 'last day of this month')->endOf('day');
        } else {
            $start_at = Date::parse('first day of this month')->startOf('day');
            $end_at = Date::parse('last day of this month')->endOf('day');
        }

        $salespeople = Salesperson::get()->pluck('nama_sales', 'id')->prepend("Pilih Sales", '');
        $querySalesOrders = Order::query()
            ->with([
                'salesperson', 'order_details',
                'tagihan', 'pembayarans',
                'invoices', 'invoices.invoice_details',
            ])
            ->whereBetween('date', [$start_at, $end_at]);

        if ($salesperson_id = $request->input('salesperson_id')) {
            $querySalesOrders->where('salesperson_id', $salesperson_id);
        }

        $orders = $querySalesOrders->get();

        // dd(
        //     $orders->map(fn($item) => $item->invoices->sum('nominal'))->sum(),
        //     $orders->sum('invoices.nominal')
        // );

        return view('admin.dashboard', compact('salespeople', 'start_at', 'end_at', 'orders'));
    }

    //update price
    // $invoices = Invoice::with('invoice_details')->whereIn('id', [14, 4, 7])->get();

    // foreach($invoices as $invoice) {
    //     $order = Order::with('order_details')->where('id', $invoice->order_id)->first();
    //     $order_details = $order->order_details;
    //     foreach($invoice->invoice_details as $detail) {
    //         $price = $order_details->where('product_id', $detail->product_id)->first()->price;
    //         $qty = $detail->quantity;

    //         $detail->update([
    //             'price' => $price,
    //             'total' => $qty * $price,
    //         ]);
    //     }
    // }

    //update movement
    // $stocks = StockMovement::where('type', 'kelengkapan')->where('reference', 4)->get();

    // foreach($stocks as $stock) {
    //     $product = $stock->product;
    //     $product->update([
    //         'stock' => DB::raw($product->stock + $stock->quantity),
    //     ]);
    // }

    // StockMovement::where('type', 'kelengkapan')->where('reference', 4)->delete();

    //update move dan quantity bonus
    // $invoice_detail = InvoiceDetail::with('bonus')->whereHas('bonus')->where('invoice_id', 4)->get();
    //         foreach($invoice_detail as $detail) {
    //             $order_detail = OrderDetail::with('bonus')->whereHas('bonus')->where('order_id', 33)->where('product_id', $detail->product_id)->first();

    //             $detail->bonus->update([
    //                 'quantity' => 0
    //             ]);

    //             $order_detail->bonus->update([
    //                 'moved' => 0
    //             ]);
    //         }

    // set_time_limit(0);
    // DB::beginTransaction();
    // try {

    //     $products = Product::with(['stock_movements' => function ($q) {
    //         $q->latest();
    //     }])->whereHas('stock_movements')->get();

    //     foreach($products as  $product) {
    //         $stock = $product->stock;
    //         foreach($product->stock_movements as $movement) {
    //             $stock_akhir = $stock;
    //             $stock_awal = $stock - $movement->quantity;
    //             $movement->update(['stock_awal' => $stock_awal, 'stock_akhir' => $stock_akhir]);
    //             $stock = $stock_awal;
    //         }
    //     }
    //     DB::commit();
    // } catch (\Exception $e) {
    //     DB::rollback();

    //     dd($e->getMessage());
    // }

    //ubah harga

    // DB::beginTransaction();
    //     try {
    //         $order = Order::with(['order_details' => function($q) {
    //             $q->where('price', 2600);
    //         }])->where('id', 18)->first();

    //         foreach($order->order_details as $order_detail) {
    //             $price = 2200;
    //             $qty = $order_detail->quantity;

    //             $order_detail->update([
    //                 'price' => $price,
    //                 'total' => $qty * $price,
    //             ]);
    //         }

    //         $invoice = Invoice::with(['invoice_details' => function($q) {
    //             $q->where('price', 2600);
    //         }])->where('id', 62)->first();

    //         foreach($invoice->invoice_details as $invoice_detail) {
    //             $price = 2200;
    //             $qty = $invoice_detail->quantity;

    //             $invoice_detail->update([
    //                 'price' => $price,
    //                 'total' => $qty * $price,
    //             ]);
    //         }

    //         $inv_edit = Invoice::with('invoice_details')->where('id', 62)->first();
    //         $inv_edit->update([
    //             'nominal' => $inv_edit->invoice_details->sum('total')
    //         ]);

    //         $order_edit = Order::with('order_details')->where('id', 18)->first();

    //         Tagihan::where('order_id', 18)->update([
    //             'total' => $order_edit->order_details->sum('total'),
    //             'tagihan' => $inv_edit->invoice_details->sum('total')
    //         ]);

    //         $orderAll = Order::with('order_details')->get();
    //         foreach($orderAll as $order) {
    //             $order->update([
    //                 'nominal' => $order->order_details->sum('total')
    //             ]);
    //         }
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         dd($e->getMessage());
    //     }


    public function god(){
        set_time_limit(0);
        $this->fixPgMoved();
    }

    public function fixPgMoved() {
        $order_packages = OrderPackage::whereHas('product', function ($query) {
            $query->where('jenjang_id', 3);
        })->whereRaw('quantity > moved')->where('order_id', 10)->get();
        foreach($order_packages as $bonus) {
            if ($bonus->product->jenjang_id === 3) {
                $bonus->moved = $bonus->quantity;
                $bonus->save();
            }
        }
        dd('nani');
    }

    public function checkDeletedProduct() {
        set_time_limit(0);
        DB::beginTransaction();
        try {
            $order_id = 14;
            $products = collect([]);
            $order = Order::with('order_details')->where('id', $order_id)->first();
            foreach($order->order_details as $order_detail) {
                if (!$order_detail->product) {
                    $products->push([
                        'id' => $order_detail->product_id,
                    ]);
                }
            }
            dd($products);
        } catch (\Exception $e) {
            DB::rollback();

            dd($e->getMessage());
        }
        dd('done');
    }

    // ngisi order id di order_packages
    // $order_details = OrderDetail::all();
    // foreach($order_details as $detail) {
    //     $bonus = OrderPackage::where('order_detail_id', $detail->id)->update(
    //         ['order_id' => $detail->order_id]
    //     );
    // }
    // dd('done');

    //mbenerke stock pg
    // DB::beginTransaction();
    // try {
    //     $products = Product::with(['stock_movements'])->whereHas('stock_movements')
    //         ->where('semester_id', 7)->where('tipe_pg', '!=', 'buku')->get();

    //     foreach($products as  $product) {
    //         $stock = 0;
    //         foreach($product->stock_movements as $movement) {
    //             $stock_awal = $stock;
    //             $stock_akhir = $stock_awal + $movement->quantity;
    //             $movement->update(['stock_awal' => $stock_awal, 'stock_akhir' => $stock_akhir]);
    //             $stock = $stock_akhir;
    //         }
    //         $product->update(['stock' => $stock]);
    //     }
    //     DB::commit();
    // } catch (\Exception $e) {
    //     DB::rollback();

    //     dd($e->getMessage());
    // }
    // dd('done');

    //mbenerke movement buku
    // DB::beginTransaction();
    // try {
    //     $products = Product::with(['stock_movements' => function ($q) {
    //         $q->latest();
    //     }])->whereHas('stock_movements')->get();

    //     foreach($products as  $product) {
    //         $stock = $product->stock;
    //         foreach($product->stock_movements as $movement) {
    //             $stock_akhir = $stock;
    //             $stock_awal = $stock - $movement->quantity;
    //             $movement->update(['stock_awal' => $stock_awal, 'stock_akhir' => $stock_akhir]);
    //             $stock = $stock_awal;
    //         }
    //     }
    //     DB::commit();
    // } catch (\Exception $e) {
    //     DB::rollback();

    //     dd($e->getMessage());
    // }

    //Ngecek movement karo stock
    // $fakap = collect();
    // $products = Product::with(['stock_movements' => function ($q) {
    //             $q->orderBy('id', 'DESC');
    //         }])->whereHas('stock_movements')->get();

    // foreach($products as $product) {
    //     if ($product->stock_movements->count() > 0) {
    //         $movement = $product->stock_movements->first();
    //         if ($product->stock !== $movement->stock_akhir) {
    //             $fakap->push([
    //                 'id' => $movement->product_id,
    //                 'stock_akhir' => $movement->stock_akhir,
    //                 'stock' => $product->stock
    //             ]);
    //         }
    //     }
    // }
    // dd($fakap);

    // $fakap = collect();
    // $products = Product::with(['stock_movements' => function ($q) {
    //             $q->orderBy('id', 'ASC');
    //         }])->whereHas('stock_movements')->get();

    // foreach($products as $product) {
    //     if ($product->stock_movements->count() > 0) {
    //         $movement = $product->stock_movements->first();
    //         if ($movement->stock_awal !== 0) {
    //             $fakap->push([
    //                 'id' => $movement->product_id,
    //                 'stock_awal' => $movement->stock_awal,
    //                 'stock' => $product->stock
    //             ]);
    //         }
    //     }
    // }
    // dd($fakap);
}
