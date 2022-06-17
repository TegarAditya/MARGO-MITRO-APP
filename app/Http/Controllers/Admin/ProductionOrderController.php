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
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

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
                $deleteGate = 'production_order_delete';
                $crudRoutePart = 'production-orders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('po_number', function ($row) {
                return $row->po_number ? $row->po_number : '';
            });
            $table->editColumn('no_spk', function ($row) {
                return $row->no_spk ? $row->no_spk : '';
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
                'productionperson_id' => $request->productionperson_id,
                'date' => $request->date,
                'total' => $request->total ?: 0,
                'type' => $request->type,
                'po_number' => ProductionOrder::generateNoPO(),
                'no_spk' => ProductionOrder::generateNoSPK(),
                'no_kwitansi' => ProductionOrder::generateNoKwitansi(),
                'created_by_id' => $user->id,
            ])->save();

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($productionOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $prod = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'production_order_id' => $productionOrder->id,
                    'order_qty' => $qty,
                    'prod_qty' => $prod,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                ];
            });

            $productionOrder->production_order_details()->createMany($order_details->all());

            DB::commit();

            return redirect()->route('admin.production-orders.edit', $productionOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load('productionperson', 'created_by', 'production_order_details');

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

        DB::beginTransaction();
        try {
            $productionOrder->forceFill([
                'productionperson_id' => $request->productionperson_id,
                'date' => $request->date,
                'total' => $request->total ?: 0,
                'type' => $request->type,
                'created_by_id' => $user->id,
            ])->save();

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($productionOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $prod = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'production_order_id' => $productionOrder->id,
                    'order_qty' => $qty,
                    'prod_qty' => $prod,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                ];
            });

            foreach ($order_details as $order_detail) {
                $exists = $productionOrder->production_order_details()->where('product_id', $order_detail['product_id'])->first() ?: new ProductionOrderDetail();

                $exists->forceFill($order_detail)->save();
            }

            // Delete items if removed
            $productionOrder->production_order_details()
                ->whereNotIn('product_id', $order_details->pluck('product_id'))
                ->forceDelete();

            DB::commit();

            return redirect()->route('admin.production-orders.edit', $productionOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load('productionperson', 'created_by');

        return view('admin.productionOrders.show', compact('productionOrder'));
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
