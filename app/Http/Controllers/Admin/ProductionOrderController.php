<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionOrderRequest;
use App\Http\Requests\StoreProductionOrderRequest;
use App\Http\Requests\UpdateProductionOrderRequest;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
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

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $products = Product::with(['media', 'category'])->get();

        return view('admin.productionOrders.create', compact('productionpeople', 'products'));
    }

    public function store(StoreProductionOrderRequest $request)
    {
        $productionOrder = ProductionOrder::create($request->all());

        return redirect()->route('admin.production-orders.index');
    }

    public function edit(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productionOrder->load('productionperson', 'created_by');

        return view('admin.productionOrders.edit', compact('productionOrder', 'productionpeople'));
    }

    public function update(UpdateProductionOrderRequest $request, ProductionOrder $productionOrder)
    {
        $productionOrder->update($request->all());

        return redirect()->route('admin.production-orders.index');
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
