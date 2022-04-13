<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Http\Requests\UpdateStockAdjustmentRequest;
use App\Http\Resources\Admin\StockAdjustmentResource;
use App\Models\StockAdjustment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockAdjustmentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_adjustment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StockAdjustmentResource(StockAdjustment::with(['product'])->get());
    }

    public function store(StoreStockAdjustmentRequest $request)
    {
        $stockAdjustment = StockAdjustment::create($request->all());

        return (new StockAdjustmentResource($stockAdjustment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StockAdjustmentResource($stockAdjustment->load(['product']));
    }

    public function update(UpdateStockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->update($request->all());

        return (new StockAdjustmentResource($stockAdjustment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
