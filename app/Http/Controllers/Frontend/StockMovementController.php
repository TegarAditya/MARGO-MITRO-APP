<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockMovementController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockMovements = StockMovement::with(['product'])->get();

        return view('frontend.stockMovements.index', compact('stockMovements'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_movement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.stockMovements.create', compact('products'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $stockMovement = StockMovement::create($request->all());

        return redirect()->route('frontend.stock-movements.index');
    }
}
