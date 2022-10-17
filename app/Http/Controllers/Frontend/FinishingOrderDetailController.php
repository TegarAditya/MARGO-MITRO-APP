<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFinishingOrderDetailRequest;
use App\Http\Requests\StoreFinishingOrderDetailRequest;
use App\Http\Requests\UpdateFinishingOrderDetailRequest;
use App\Models\Product;
use App\Models\FinishingOrder;
use App\Models\FinishingOrderDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinishingOrderDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('production_order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrderDetails = FinishingOrderDetail::with(['finishing_order', 'product'])->get();

        return view('frontend.finishingOrderDetails.index', compact('finishingOrderDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishing_orders = FinishingOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.finishingOrderDetails.create', compact('production_orders', 'products'));
    }

    public function store(StoreFinishingOrderDetailRequest $request)
    {
        $finishingOrderDetail = FinishingOrderDetail::create($request->all());

        return redirect()->route('frontend.production-order-details.index');
    }

    public function edit(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishing_orders = FinishingOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $finishingOrderDetail->load('finishing_order', 'product');

        return view('frontend.finishingOrderDetails.edit', compact('productionOrderDetail', 'production_orders', 'products'));
    }

    public function update(UpdateFinishingOrderDetailRequest $request, FinishingOrderDetail $finishingOrderDetail)
    {
        $finishingOrderDetail->update($request->all());

        return redirect()->route('frontend.production-order-details.index');
    }

    public function show(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrderDetail->load('finishing_order', 'product');

        return view('frontend.finishingOrderDetails.show', compact('productionOrderDetail'));
    }

    public function destroy(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrderDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinishingOrderDetailRequest $request)
    {
        FinishingOrderDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
