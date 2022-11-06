<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionOrderRequest;
use App\Http\Requests\StoreProductionOrderRequest;
use App\Http\Requests\UpdateProductionOrderRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\Productionperson;
use App\Models\Order;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Date;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use NumberFormatter;

class ProductionOrderController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductionOrder::with(['productionperson', 'created_by'])->select(sprintf('%s.*', (new ProductionOrder())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_show';
                $editGate = 'production_order_edit';
                $deleteGate = 'production_order_delete_hidden'; //order delete hidden
                $crudRoutePart = 'production-orders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('no_order', function ($row) {
                return $row->no_order ? $row->no_order : '';
            });
            $table->addColumn('productionperson_name', function ($row) {
                return $row->productionperson ? $row->productionperson->name : '';
            });

            $table->addColumn('created_by_name', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productionperson', 'created_by']);

            return $table->make(true);
        }

        return view('admin.productionOrders.index');
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

        return view('admin.productionOrders.dashboard', compact('salespeople', 'start_at', 'end_at', 'orders'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::get();

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.productionOrders.create', compact('productionpeople', 'products', 'categories'));
    }

    public function store(StoreProductionOrderRequest $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'productionperson_id' => 'required|exists:productionpeople,id',
            'products' => 'required|array|min:1',
            // 'bahans' => 'required|array|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $productionOrder = new ProductionOrder();

            $productionOrder->forceFill([
                'user_id' => $user->id,
                'order_id' => $request->order_id,
                'finishing_order_id' => $request->finishing_order_id,
                'productionperson_id' => $request->productionperson_id,
                'no_order' => ProductionOrder::generateNoPO(),
                'type' => $request->type,
                'date' => $request->date,
                'total' => $request->total ?: 0,
            ])->save();

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($productionOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $group = (int) $request->products[$item->id]['group'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'order_id' => $request->order_id,
                    'production_order_id' => $productionOrder->id,
                    'productionperson_id' => $request->productionperson_id,
                    'finishing_order_id' => $request->finishing_order_id,
                    'order_qty' => $qty,
                    'group' => $group,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                ];
            });

            $productionOrder->production_order_details()->createMany($order_details->all());

            DB::commit();

            Alert::success('Success', 'Production Order berhasil di simpan');

            return redirect()->route('admin.production-orders.edit', $productionOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load([
            'productionperson',
            'created_by',
            'production_order_details', 'production_order_details.production_order',
        ]);

        $productionpeople = Productionperson::get();

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.productionOrders.edit', compact('productionOrder', 'productionpeople', 'products', 'categories'));
    }

    public function update(UpdateProductionOrderRequest $request, ProductionOrder $productionOrder)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'productionperson_id' => 'required|exists:productionpeople,id',
            'products' => 'required|array|min:1',
            // 'bahans' => 'required|array|min:1',
        ]);

        $user = $request->user();
        $old_status = (int) $request->status;

        if ($old_status === 0 && $productionOrder->status !== 0) {
            Alert::error('Error', 'Production Order sedang dalam proses dan tidak dapat diubah.');

            return redirect()->route('admin.production-orders.edit', $productionOrder->id);
        }

        DB::beginTransaction();
        try {
            $productionOrder->forceFill([
                'user_id' => $user->id,
                'order_id' => $request->order_id,
                'finishing_order_id' => $request->finishing_order_id,
                'productionperson_id' => $request->productionperson_id,
                'type' => $request->type,
                'date' => $request->date,
                'total' => $request->total ?: 0,
            ])->save();

            $products = Product::whereIn('id', array_keys($request->products))->get();
            $order_details = $products->map(function($item) use ($productionOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $group = (int) $request->products[$item->id]['group'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;
                $check = (float) $request->products[$item->id]['check'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'order_id' => $request->order_id,
                    'production_order_id' => $productionOrder->id,
                    'productionperson_id' => $request->productionperson_id,
                    'finishing_order_id' => $request->finishing_order_id,
                    'order_qty' => $qty,
                    'group' => $group,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                    'is_check' => $check,
                ];
            });

            $product_stocks = collect([]);

            foreach ($order_details as $order_detail) {
                $exists = $productionOrder->production_order_details()->where('product_id', $order_detail['product_id'])->first() ?: new ProductionOrderDetail();

                if ($exists->is_check != $order_detail['is_check'] && $order_detail['is_check'] == 1) {
                    $product_stocks->put($order_detail['product_id'], $order_detail['order_qty']);
                }

                $exists->forceFill($order_detail)->save();
            }

            if ($product_stocks->count()) {
                $upsert_products = $product_stocks->map(function($item, $key) use ($products) {
                    $product = $products->find($key);

                    return [
                        'id' => $key,
                        'stock' => !$product ? $item : ($product->stock + $item),
                    ];
                });

                $upsert_stocks = $product_stocks->map(function($item, $key) use ($productionOrder) {
                    return [
                        'id' => null,
                        'product_id' => $key,
                        'reference' => $productionOrder->id,
                        'type' => 'production_order',
                        'quantity' => $item,
                    ];
                });

                Product::upsert($upsert_products->all(), ['id']);
                StockMovement::upsert($upsert_stocks->all(), ['id']);
            }

            // Delete items if removed
            $productionOrder->production_order_details()
                ->whereNotIn('product_id', $order_details->pluck('product_id'))
                ->forceDelete();

            DB::commit();

            Alert::success('Success', 'Production Order berhasil di simpan');

            return redirect()->route('admin.production-orders.edit', $productionOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load([
            'productionperson', 'created_by',
            'production_order_details', 'production_order_details.product',
        ]);

        switch (request('print')) {
            case 'spk':
                return view('admin.productionOrders.prints.perintah-kerja', compact('productionOrder'));
            case 'kwitansi':
                return view('admin.productionOrders.prints.kwitansi', compact('productionOrder'));
        }

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.productionOrders.show', compact('productionOrder', 'products', 'categories'));
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductionOrderRequest $request)
    {
        ProductionOrder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
