<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Salesperson;
use App\Models\Semester;
use App\Models\AlamatSale;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;

class FakturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with(['order'])->select(sprintf('%s.*', (new Invoice())->table))->where('nominal', '>=', 0)->whereNull('gudang');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return '
                    <a class="px-1" href="'.route('admin.faktur.edit', $row->id).'" title="Edit">
                        <i class="fas fa-edit fa-lg"></i>
                    </a>
                ';
            });

            $table->editColumn('no_suratjalan', function ($row) {
                return $row->no_suratjalan ? $row->no_suratjalan : '';
            });
            $table->editColumn('no_invoice', function ($row) {
                return 'No Invoice : '. $row->no_invoice .'<br>'.
                'No Surat Jalan : '. $row->no_suratjalan;
            });
            $table->editColumn('date', function ($row) {
                return $row->date;
            });

            $table->addColumn('sales', function ($row) {
                return $row->order->salesperson ? $row->order->salesperson->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'date', 'no_invoice']);

            return $table->make(true);
        }

        return view('admin.faktur.index');
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with(['order'])->select(sprintf('%s.*', (new Invoice())->table))->where('nominal', '>=', 0)->where('gudang', 1);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return '
                    <a class="px-1" href="'.route('admin.faktur.show', $row->id).'" title="Show">
                        <i class="fas fa-eye text-success fa-lg"></i>
                    </a>
                    <a class="px-1" href="'.route('admin.faktur.show',[$row->id, 'print' => 'sj']).'" title="Cetak">
                        <i class="fas fa-print text-danger fa-lg"></i>
                    </a>
                ';
            });

            $table->editColumn('no_suratjalan', function ($row) {
                return $row->no_suratjalan ? $row->no_suratjalan : '';
            });
            $table->editColumn('no_invoice', function ($row) {
                return 'No Invoice : '. $row->no_invoice .'<br>'.
                'No Surat Jalan : '. $row->no_suratjalan;
            });
            $table->editColumn('date', function ($row) {
                return $row->date;
            });

            $table->addColumn('sales', function ($row) {
                return $row->order->salesperson ? $row->order->salesperson->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'date', 'no_invoice']);

            return $table->make(true);
        }

        return view('admin.faktur.history');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::find($id);
        $invoice->load([
            'invoice_details',
            'order',
        ]);

        if (request('print') === 'sj') {
            $product_detail = InvoiceDetail::with('bonus', 'product')->where('invoice_id', $invoice->id)->get();

            $pg_array = collect();

            $details = $product_detail->each(function ($item) use ($product_detail, $pg_array) {
                if ($bonus = $item->bonus) {
                    $bonus_id = $bonus->product_id;
                    $bonus_qty = $bonus->quantity;
                    $ada = $product_detail->firstWhere('product_id', '=', $bonus_id);
                    if ($ada) {
                        $pg_array->push([
                            'id' => $ada->id
                        ]);
                        $bonus_qty += $ada->quantity;
                    }
                    $item->bonus->quantity = $bonus_qty;
                }
            });

            $details = $details->whereNotIn('id', $pg_array->pluck('id'));
            $inv_details = $details->where('product.tipe_pg', '===', 'non_pg')->sortBy('product.kelas_id')
                                ->sortBy('product.tiga_nama')->sortBy('product.jenjang_id');
            $pg_details = $details->where('product.tipe_pg', '!==', 'non_pg')->sortBy('product.kelas_id')
                                ->sortBy('product.tiga_nama')->sortBy('product.jenjang_id');

            $total_buku = $inv_details->sum('quantity');
            $total_pg = $pg_details->sum('quantity');
            $total_kelengkapan = $inv_details->sum('bonus.quantity') + $total_pg;

            return view('admin.faktur.prints.surat-jalan', compact('invoice', 'inv_details', 'pg_details', 'total_buku', 'total_kelengkapan'));
        }

        return view('admin.faktur.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::find($id);

        $orders = Order::whereHas('order_details')
            ->get()->mapWithKeys(function($item) {
                return [$item->id => $item->no_order];
            })->prepend(trans('global.pleaseSelect'), '');
        $order_details = OrderDetail::with(['product', 'product.media'])
            ->whereHas('product')
            ->get();

        $invoice->load('invoice_details', 'invoice_details.product', 'invoice_details.bonus',
            'order', 'order.invoices', 'order.invoices.invoice_details', 'order.tagihan');

        $order = null;
        if ($order = $invoice->order) {
            $order_details = $order_details->where('order_id', $order->id);
        }

        $invoice_details = $invoice->invoice_details->map(function($item) use ($order_details) {
            $item->order_detail = $order_details->where('product_id', $item->product_id)->first();

            return $item;
        });

        return view('admin.faktur.edit', compact('orders', 'order_details', 'invoice', 'order', 'invoice_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'products' => 'required|array|min:1',
        ]);

        $invoice = Invoice::find($id);

        $order = Order::findOrFail($invoice->order_id);

        $order->load([
            'order_details', 'order_details.bonus', 'order_details.product'
        ]);

        DB::beginTransaction();
        try {
            $multiplier = 1;
            $invoice->forceFill([
                'nominal' => $multiplier * (float) $request->nominal,
                'gudang' => 1
            ])->save();

            $invoice->load([
                'invoice_details', 'invoice_details.product', 'invoice_details.bonus', 'invoice_details.bonus.product'
            ]);

            $order->tagihan()->update([
                'tagihan' => $order->invoices()->sum('nominal') ?: 0,
                'retur' => abs($order->invoices()->where('nominal', '<', 0)->sum('nominal')) ?: 0
            ]);

            // Restore to previous data
            foreach ($invoice->invoice_details as $invoice_detail) {
                if ($product = $invoice_detail->product) {
                    $product->update([
                        'stock' => $product->stock + $invoice_detail->quantity
                    ]);
                }
                $order->order_details()->where('product_id', $invoice_detail->product_id)->update([
                    'moved' => DB::raw("order_details.moved - $invoice_detail->quantity"),
                ]);

                $order_detail = $order->order_details()->where('product_id', $invoice_detail->product_id)->first();

                if ($bonus = $invoice_detail->bonus) {
                    $bonus_product = Product::find($bonus->product_id);
                    $bonus_product->update([
                        'stock' => $bonus_product->stock + $bonus->quantity
                    ]);
                    $order_detail->bonus->update([
                        'moved' => $order_detail->bonus->moved - $bonus->quantity
                    ]);
                }
            }

            // Update with new items
            $products = Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($invoice, $order, $request, $multiplier) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;
                $qty_bonus = $request->products[$item->id]['bonus'] ?? null;

                $qty = $qty * $multiplier;

                $item->stock_movements()->updateOrCreate([
                    'reference' => $invoice->id,
                    'type' => 'invoice',
                    'product_id' => $item->id,
                ],[
                    'quantity' => -1 * $qty,
                    'stock_awal' => $item->stock,
                    'stock_akhir' => $item->stock - $qty,
                    'date' => $request->date,
                ]);
                $item->update([ 'stock' => $item->stock - $qty ]);

                $order->order_details()->where('product_id', $item->id)->update([
                    'moved' => DB::raw("order_details.moved + $qty"),
                ]);

                $invoice_detail = InvoiceDetail::updateOrCreate([
                    'product_id' => $item->id,
                    'invoice_id' => $invoice->id
                ],[
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ]);

                if (isset($qty_bonus)) {
                    $order_package = $order->order_details()->where('product_id', $item->id)->first();
                    $order_bonus = $order_package->bonus;

                    $bonus = InvoicePackage::updateOrCreate([
                        'product_id' => $order_bonus->product_id,
                        'invoice_detail_id' => $invoice_detail->id
                    ], [
                        'quantity' => $qty_bonus
                    ]);
                    $order_bonus->update([
                        'moved' => $order_bonus->move + $qty_bonus,
                    ]);

                    $bonus_product = Product::find($order_bonus->product_id);

                    $bonus_product->stock_movements()->updateOrCreate([
                        'reference' => $invoice->id,
                        'type' => 'kelengkapan',
                        'product_id' => $bonus_product->id
                    ], [
                        'quantity' => -1 * $qty_bonus,
                        'stock_awal' => $bonus_product->stock,
                        'stock_akhir' => $bonus_product->stock - $qty_bonus,
                        'date' => $request->date,
                    ]);
                    $bonus_product->update([ 'stock' => $bonus_product->stock - $qty_bonus ]);
                }
            });

            DB::commit();

            Alert::success('Success', 'Faktur Berhasil Disimpan');

            return redirect()->route('admin.faktur.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
