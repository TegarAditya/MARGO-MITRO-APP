<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePackage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Salesperson;
use App\Models\Semester;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('invoice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Invoice::with(['order'])->select(sprintf('%s.*', (new Invoice())->table));
            if (!empty($request->date)) {
                $dates = explode(' - ', $request->date);

                $start = Date::parse($dates[0])->startOfDay();
                $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();

                $query->whereBetween('date', [$start, $end]);
            }

            if (!empty($request->sales)) {
                $sales = $request->sales;
                $query->whereHas('order', function ($q) use($sales) {
                    $q->where('salesperson_id', $sales);
                });
            }

            if (!empty($request->semester)) {
                $semester = $request->semester;
                $query->whereHas('order', function($q) use($semester) {
                    $q->where('semester_id', $semester);
                });
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'invoice_show';
                $editGate = 'invoice_edit';
                $deleteGate = 'invoice_delete';
                $crudRoutePart = 'invoices';
                $parent = 'orders';
                $idParent = $row->order->id;

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('no_suratjalan', function ($row) {
                return $row->no_suratjalan ? $row->no_suratjalan : '';
            });
            $table->editColumn('no_invoice', function ($row) {
                return 'No Invoice : '. $row->no_invoice .'<br>'.
                'No Surat Jalan : '. $row->no_suratjalan;
            });
            $table->editColumn('date', function ($row) {
                $is_out = ($row->nominal > 0 ? true : false);
                return $row->date .'<span class="ml-1 '. ($is_out ? 'text-success' : 'text-danger' ).'">'. ($is_out ? '<i class="fa fa-arrow-up"></i>' : '<i class="fa fa-arrow-down"></i>' ) .'</span> ';
            });
            $table->addColumn('order', function ($row) {
                return !$row->order ? '-' : '<a href="'.route('admin.orders.show', $row->order->id).'">'.$row->order->no_order.'</a>';
            });

            $table->addColumn('sales', function ($row) {
                return $row->order->salesperson ? $row->order->salesperson->name : '';
            });

            $table->editColumn('nominal', function ($row) {
                return $row->nominal ? abs($row->nominal) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'date', 'no_invoice']);

            return $table->make(true);
        }

        $salespersons = Salesperson::pluck('name', 'id')->prepend('Semua Sales Person', '');
        $semesters = Semester::pluck('name', 'id')->prepend('Semua Semester', '');

        return view('admin.invoices.index', compact('salespersons', 'semesters'));
    }

    public function create()
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::with('salesperson')->whereHas('tagihan', function($q){
            $q->whereRaw('total > tagihan ');
        })->get()->mapWithKeys(function($item) {
            return [$item->id => $item->no_order .' - '.$item->salesperson->name ];
        })->prepend(trans('global.pleaseSelect'), '');
        $order_details = OrderDetail::with(['product', 'product.media', 'bonus'])
            ->whereHas('product')
            ->get();

        $invoice = new Invoice();
        $invoice_details = collect([]);
        $order = new Order();

        if ($order_id = request('order_id')) {
            $order = Order::with('invoices', 'tagihan')->findOrFail($order_id);
            $order_details = $order_details->where('order_id', $order_id);
        }

        return view('admin.invoices.create', compact('orders', 'order_details', 'invoice', 'order', 'invoice_details'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'order_id' => 'required|exists:orders,id',
            'products' => 'required|array|min:1',
        ]);

        $order = Order::findOrFail($request->order_id);

        DB::beginTransaction();
        try {
            $multiplier = -1 * (int) $request->get('invoice_type', -1);
            $invoice = Invoice::create([
                'no_suratjalan' => Invoice::generateNoSJ($order->semester_id),
                'no_invoice' => Invoice::generateNoInvoice($order->semester_id),
                'date' => $request->date,
                'nominal' => $multiplier * (float) $request->nominal,
                'order_id' => $request->order_id,
            ]);

            $order->tagihan()->update([
                'tagihan' => $order->invoices()->sum('nominal') ?: 0
            ]);

            // dd($request->all());

            $products = Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($invoice, $order, $request, $multiplier) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;
                $qty_bonus = $request->products[$item->id]['bonus'] ?? null;

                $qty = $qty * $multiplier;

                $item->stock_movements()->create([
                    'reference' => $invoice->id,
                    'type' => 'invoice',
                    'quantity' => -1 * $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock - $qty ]);

                $order->order_details()->where('product_id', $item->id)->update([
                    'moved' => DB::raw("order_details.moved + $qty"),
                ]);

                $invoice_detail = InvoiceDetail::create([
                    'product_id' => $item->id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ]);

                if ($qty_bonus) {
                    $order_package = $order->order_details()->where('product_id', $item->id)->first();

                    $bonus = InvoicePackage::create([
                        'product_id' => $item->id,
                        'invoice_detail_id' => $invoice_detail->id,
                        'quantity' => $qty_bonus
                    ]);

                    $order_bonus = $order_package->bonus;
                    $order_bonus->update([
                        'moved' => $order_bonus->move + $qty_bonus,
                    ]);

                    $bonus_product = Product::find($order_bonus->product_id);

                    $bonus_product->stock_movements()->create([
                        'reference' => $invoice->id,
                        'type' => 'invoice',
                        'quantity' => -1 * $qty,
                        'product_id' => $bonus_product->id,
                    ]);
                    $bonus_product->update([ 'stock' => $bonus_product->stock - $qty_bonus ]);
                }
            });

            DB::commit();

            Alert::success('Success', 'Invoice berhasil dibuat');

            if ($request->redirect) {
                return redirect($request->redirect)->with('activeTabs', 'invoice');
            }

            return redirect()->route('admin.invoices.edit', $invoice->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::get()->mapWithKeys(function($item) {
            return [$item->id => $item->no_order];
        })->prepend(trans('global.pleaseSelect'), '');
        $order_details = OrderDetail::with(['product', 'product.media'])
            ->whereHas('product')
            ->get();

        $invoice->load('invoice_details', 'order', 'order.invoices', 'order.invoices.invoice_details', 'order.tagihan');

        $order = null;
        if ($order = $invoice->order) {
            $order_details = $order_details->where('order_id', $order->id);
        }

        $invoice_details = $invoice->invoice_details->map(function($item) use ($order_details) {
            $item->order_detail = $order_details->where('product_id', $item->product_id)->first();

            return $item;
        });

        return view('admin.invoices.edit', compact('orders', 'order_details', 'invoice', 'order', 'invoice_details'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $request->validate([
            'date' => 'required|date',
            'order_id' => 'required|exists:orders,id',
            'products' => 'required|array|min:1',
        ]);

        $order = Order::findOrFail($request->order_id);

        DB::beginTransaction();
        try {
            $multiplier = -1 * (int) $request->get('invoice_type', -1);
            $invoice->forceFill([
                'no_suratjalan' => Invoice::generateNoSJ($order->semester_id),
                'no_invoice' => Invoice::generateNoInvoice($order->semester_id),
                'date' => $request->date,
                'nominal' => $multiplier * (float) $request->nominal,
                'order_id' => $request->order_id,
            ])->save();

            $order->tagihan()->update([
                'tagihan' => $order->invoices()->sum('nominal') ?: 0
            ]);

            $invoice->load([
                'invoice_details', 'invoice_details.product',
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
            }

            // Update with new items
            $invoice_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($invoice, $order, $request, $multiplier) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                $qty = $qty * $multiplier;

                $item->stock_movements()->updateOrCreate([
                    'reference' => $invoice->id,
                    'type' => 'invoice',
                    'product_id' => $item->id,
                ],[
                    'reference' => $invoice->id,
                    'type' => 'invoice',
                    'quantity' => -1 * $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock - $qty ]);

                $order->order_details()->where('product_id', $item->id)->update([
                    'moved' => DB::raw("order_details.moved + $qty"),
                ]);

                return [
                    'product_id' => $item->id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ];
            });

            foreach ($invoice_details as $invoice_detail) {
                $exists = $invoice->invoice_details->where('product_id', $invoice_detail['product_id'])->first() ?: new InvoiceDetail;

                $exists->forceFill($invoice_detail)->save();
            }

            // Delete items if removed
            $invoice->invoice_details()
                ->whereNotIn('product_id', $invoice_details->pluck('product_id'))
                ->forceDelete();
            StockMovement::where('reference', $invoice->id)
                ->where('type', 'invoice')
                ->whereNotIn('product_id', $invoice_details->pluck('product_id'))
                ->delete();

            DB::commit();

            if ($request->redirect) {
                return redirect($request->redirect)->with('activeTabs', 'invoice');
            }

            return redirect()->route('admin.invoices.edit', $invoice->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice->load([
            'invoice_details',
            'order', 'order.invoices', 'order.invoices.invoice_details', 'order.tagihan', 'invoice_details.bonus',
        ]);

        switch (request('print')) {
            case 'sj':
                return view('admin.invoices.prints.surat-jalan', compact('invoice'));
            case 'inv':
                return view('admin.invoices.prints.faktur', compact('invoice'));
        }

        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        abort_if(Gate::denies('invoice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::findOrFail($invoice->order_id);

        DB::beginTransaction();
        try {
            $invoice->load([
                'invoice_details', 'invoice_details.product',
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
            }

            // Delete items if removed
            $invoice->invoice_details()
                ->whereIn('product_id', $invoice->invoice_details->pluck('product_id'))
                ->forceDelete();
            StockMovement::where('reference', $invoice->id)
                ->where('type', 'invoice')
                ->whereIn('product_id', $invoice->invoice_details->pluck('product_id'))
                ->delete();

            $invoice->delete();

            $order->tagihan()->update([
                'tagihan' => $order->invoices()->sum('nominal') ?: 0
            ]);

            DB::commit();

            return redirect()->route('admin.invoices.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function massDestroy(MassDestroyInvoiceRequest $request)
    {
        // Invoice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function fakturRetur(Request $request)
    {
        abort_if(Gate::denies('invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::with('tagihans')->whereHas('tagihans', function($q){
            $q->whereRaw('tagihan > saldo')
            ->whereRaw('total > tagihan ');
         })->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if ($request->salesman) {
            $sales_id = $request->salesman;
            $order_details = OrderDetail::with(['product', 'product.media'])
                ->where('moved', '>', 0)
                ->whereHas('product')
                ->whereHas('order', function($q) use ($sales_id){
                    $q->where('salesperson_id', $sales_id);
                })
                ->orderByDesc('moved')
                ->get()
                ->unique('product_id')
                ->values();
        } else {
            $orders = collect([]);
            $order_details = collect([]);
        }

        $invoice = new Invoice();
        $invoice_details = collect([]);

        return view('admin.invoices.return', compact('order_details', 'invoice', 'invoice_details', 'sales'));
    }

    public function fakturReturSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
        ]);

        $collection = collect($request->products);

        $all = $collection->map(function ($item, $key) {
            return [
                'nominal' => $item['qty'] * $item['price'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'order_id' => $item['order_id'],
                'product_id' => $key
            ];
        });

        $row = $all->groupBy('order_id');
        $multiplier = -1;

        DB::beginTransaction();
        try {
            foreach($row as $key => $value) {
                $order = Order::findOrFail($key);

                foreach($value as $element) {
                    $invoice = Invoice::create([
                        'no_suratjalan' => Invoice::generateNoSJ($order->semester_id),
                        'no_invoice' => Invoice::generateNoInvoice($order->semester_id),
                        'date' => $request->date,
                        'nominal' => $multiplier * (float) $value->sum('nominal'),
                        'order_id' => $order->id
                    ]);

                    $order->tagihan()->update([
                        'tagihan' => $order->invoices()->sum('nominal') ?: 0
                    ]);

                    $products = Product::where('id', $element['product_id'])->get()->map(function($item) use ($invoice, $order, $element, $multiplier) {
                        $qty = (int) $element['qty'] ?: 0;
                        $price = (float) $element['price'] ?: 0;

                        $qty = $qty * $multiplier;

                        $item->stock_movements()->create([
                            'reference' => $invoice->id,
                            'type' => 'invoice',
                            'quantity' => -1 * $qty,
                            'product_id' => $item->id,
                        ]);
                        $item->update([ 'stock' => $item->stock - $qty ]);

                        $order->order_details()->where('product_id', $item->id)->update([
                            'moved' => DB::raw("order_details.moved + $qty"),
                        ]);

                        return [
                            'product_id' => $item->id,
                            'invoice_id' => $invoice->id,
                            'quantity' => $qty,
                            'price' => $price,
                            'total' => $qty * $price,
                        ];
                    });

                    $invoice->invoice_details()->createMany($products->all());
                }
            }

            DB::commit();

            Alert::success('Success', 'Invoice berhasil dibuat');

            return redirect()->route('admin.invoices.retur');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }
}
