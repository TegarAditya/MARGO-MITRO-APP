<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('stock_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = StockMovement::with(['product'])->select(sprintf('%s.*', (new StockMovement())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'stock_movement_show';
                $editGate = 'stock_movement_edit';
                $deleteGate = 'stock_movement_delete';
                $crudRoutePart = 'stock-movements';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? StockMovement::TYPE_SELECT[$row->type] : '';
            });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        return view('admin.stockMovements.index');
    }

    public function create()
    {
        abort_if(Gate::denies('stock_movement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockMovements.create', compact('products'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $stockMovement = StockMovement::create($request->all());

        return redirect()->route('admin.stock-movements.index');
    }

    public function show(StockMovement $stockMovement)
    {
        abort_if(Gate::denies('stock_movement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockMovement->load('product');

        return view('admin.stockMovements.show', compact('stockMovement'));
    }
}
