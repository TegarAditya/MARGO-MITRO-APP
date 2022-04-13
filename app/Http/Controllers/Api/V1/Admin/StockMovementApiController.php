<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Resources\Admin\StockMovementResource;
use App\Models\StockMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockMovementApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StockMovementResource(StockMovement::with(['product'])->get());
    }

    public function store(StoreStockMovementRequest $request)
    {
        $stockMovement = StockMovement::create($request->all());

        return (new StockMovementResource($stockMovement))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
