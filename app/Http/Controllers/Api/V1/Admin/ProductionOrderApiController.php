<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionOrderRequest;
use App\Http\Requests\UpdateProductionOrderRequest;
use App\Http\Resources\Admin\ProductionOrderResource;
use App\Models\ProductionOrder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionOrderApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductionOrderResource(ProductionOrder::with(['productionperson', 'created_by'])->get());
    }

    public function store(StoreProductionOrderRequest $request)
    {
        $productionOrder = ProductionOrder::create($request->all());

        return (new ProductionOrderResource($productionOrder))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductionOrderResource($productionOrder->load(['productionperson', 'created_by']));
    }

    public function update(UpdateProductionOrderRequest $request, ProductionOrder $productionOrder)
    {
        $productionOrder->update($request->all());

        return (new ProductionOrderResource($productionOrder))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
