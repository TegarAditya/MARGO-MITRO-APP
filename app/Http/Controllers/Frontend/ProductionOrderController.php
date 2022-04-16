<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionOrderRequest;
use App\Http\Requests\StoreProductionOrderRequest;
use App\Http\Requests\UpdateProductionOrderRequest;
use App\Models\ProductionOrder;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionOrderController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrders = ProductionOrder::with(['productionperson', 'created_by'])->get();

        return view('frontend.productionOrders.index', compact('productionOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.productionOrders.create', compact('productionpeople'));
    }

    public function store(StoreProductionOrderRequest $request)
    {
        $productionOrder = ProductionOrder::create($request->all());

        return redirect()->route('frontend.production-orders.index');
    }

    public function edit(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productionOrder->load('productionperson', 'created_by');

        return view('frontend.productionOrders.edit', compact('productionOrder', 'productionpeople'));
    }

    public function update(UpdateProductionOrderRequest $request, ProductionOrder $productionOrder)
    {
        $productionOrder->update($request->all());

        return redirect()->route('frontend.production-orders.index');
    }

    public function show(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load('productionperson', 'created_by');

        return view('frontend.productionOrders.show', compact('productionOrder'));
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
