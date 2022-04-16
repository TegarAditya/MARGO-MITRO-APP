<?php

namespace App\Http\Controllers\Frontend;

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

class ProductionOrderDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('production_order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrderDetails = ProductionOrderDetail::with(['production_order', 'product'])->get();

        return view('frontend.productionOrderDetails.index', compact('productionOrderDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $production_orders = ProductionOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.productionOrderDetails.create', compact('production_orders', 'products'));
    }

    public function store(StoreProductionOrderDetailRequest $request)
    {
        $productionOrderDetail = ProductionOrderDetail::create($request->all());

        return redirect()->route('frontend.production-order-details.index');
    }

    public function edit(ProductionOrderDetail $productionOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $production_orders = ProductionOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productionOrderDetail->load('production_order', 'product');

        return view('frontend.productionOrderDetails.edit', compact('productionOrderDetail', 'production_orders', 'products'));
    }

    public function update(UpdateProductionOrderDetailRequest $request, ProductionOrderDetail $productionOrderDetail)
    {
        $productionOrderDetail->update($request->all());

        return redirect()->route('frontend.production-order-details.index');
    }

    public function show(ProductionOrderDetail $productionOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrderDetail->load('production_order', 'product');

        return view('frontend.productionOrderDetails.show', compact('productionOrderDetail'));
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
