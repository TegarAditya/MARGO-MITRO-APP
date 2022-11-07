<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFinishingOrderRequest;
use App\Http\Requests\StoreFinishingOrderRequest;
use App\Http\Requests\UpdateFinishingOrderRequest;
use App\Models\FinishingOrder;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinishingOrderController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrders = FinishingOrder::with(['productionperson', 'created_by'])->get();

        return view('frontend.finishingOrders.index', compact('finishingOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.finishingOrders.create', compact('productionpeople'));
    }

    public function store(StoreFinishingOrderRequest $request)
    {
        $finishingOrder = FinishingOrder::create($request->all());

        return redirect()->route('frontend.finishing-orders.index');
    }

    public function edit(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $finishingOrder->load('productionperson', 'created_by');

        return view('frontend.finishingOrders.edit', compact('finishingOrder', 'productionpeople'));
    }

    public function update(UpdateFinishingOrderRequest $request, FinishingOrder $finishingOrder)
    {
        $finishingOrder->update($request->all());

        return redirect()->route('frontend.finishing-orders.index');
    }

    public function show(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrder->load('productionperson', 'created_by');

        return view('frontend.finishingOrders.show', compact('finishingOrder'));
    }

    public function destroy(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinishingOrderRequest $request)
    {
        FinishingOrder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
