<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductionOrderDetailRequest;
use App\Http\Requests\StoreProductionOrderDetailRequest;
use App\Http\Requests\UpdateProductionOrderDetailRequest;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductionOrderDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('production_order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductionOrderDetail::with(['production_order', 'product'])->select(sprintf('%s.*', (new ProductionOrderDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_detail_show';
                $editGate = 'production_order_detail_edit';
                $deleteGate = 'production_order_detail_delete';
                $crudRoutePart = 'production-order-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('production_order_po_number', function ($row) {
                return $row->production_order ? $row->production_order->po_number : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('order_qty', function ($row) {
                return $row->order_qty ? $row->order_qty : '';
            });
            $table->editColumn('prod_qty', function ($row) {
                return $row->prod_qty ? $row->prod_qty : '';
            });
            $table->editColumn('ongkos_satuan', function ($row) {
                return $row->ongkos_satuan ? $row->ongkos_satuan : '';
            });
            $table->editColumn('ongkos_total', function ($row) {
                return $row->ongkos_total ? $row->ongkos_total : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'production_order', 'product']);

            return $table->make(true);
        }

        return view('admin.productionOrderDetails.index');
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $production_orders = ProductionOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productionOrderDetails.create', compact('production_orders', 'products'));
    }

    public function store(StoreProductionOrderDetailRequest $request)
    {
        $productionOrderDetail = ProductionOrderDetail::create($request->all());

        return redirect()->route('admin.production-order-details.index');
    }

    public function edit(ProductionOrderDetail $productionOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $production_orders = ProductionOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productionOrderDetail->load('production_order', 'product');

        return view('admin.productionOrderDetails.edit', compact('productionOrderDetail', 'production_orders', 'products'));
    }

    public function update(UpdateProductionOrderDetailRequest $request, ProductionOrderDetail $productionOrderDetail)
    {
        $productionOrderDetail->update($request->all());

        return redirect()->route('admin.production-order-details.index');
    }

    public function show(ProductionOrderDetail $productionOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrderDetail->load('production_order', 'product');

        return view('admin.productionOrderDetails.show', compact('productionOrderDetail'));
    }

    public function destroy(ProductionOrderDetail $productionOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrderDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductionOrderDetailRequest $request)
    {
        ProductionOrderDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
