<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockAdjustmentRequest;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Http\Requests\UpdateStockAdjustmentRequest;
use App\Models\Product;
use App\Models\StockAdjustment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_adjustment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustments = StockAdjustment::with(['product'])->get();

        return view('frontend.stockAdjustments.index', compact('stockAdjustments'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_adjustment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.stockAdjustments.create', compact('products'));
    }

    public function store(StoreStockAdjustmentRequest $request)
    {
        $stockAdjustment = StockAdjustment::create($request->all());

        return redirect()->route('frontend.stock-adjustments.index');
    }

    public function edit(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stockAdjustment->load('product');

        return view('frontend.stockAdjustments.edit', compact('products', 'stockAdjustment'));
    }

    public function update(UpdateStockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->update($request->all());

        return redirect()->route('frontend.stock-adjustments.index');
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustment->load('product');

        return view('frontend.stockAdjustments.show', compact('stockAdjustment'));
    }

    public function destroy(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustment->delete();

        return back();
    }

    public function massDestroy(MassDestroyStockAdjustmentRequest $request)
    {
        StockAdjustment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
