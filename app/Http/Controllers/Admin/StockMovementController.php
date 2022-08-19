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
                if ($row->type == 'adjustment') {
                    return '<a href="'.route('admin.stock-adjustments.show', $row->reference).'">Reference</a>';
                } else if ($row->type == 'faktur') {
                    return '<a href="'.route('admin.invoices.show', $row->reference).'">Reference</a>';
                } else if ($row->type == 'order') {
                    return '<a href="'.route('admin.orders.show', $row->reference).'">Reference</a>';
                } else if ($row->type == 'invoice') {
                    return '<a href="'.route('admin.invoices.show', $row->reference).'">Reference</a>';
                } else if ($row->type == 'realisasi') {
                    return '<a href="'.route('admin.realisasis.show', $row->reference).'">Reference</a>';
                }

                return $row->reference ? $row->reference : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? StockMovement::TYPE_SELECT[$row->type] : '';
            });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->nama_buku : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'reference']);

            return $table->make(true);
        }

        $products = Product::get();

        return view('admin.stockMovements.index', compact('products'));
    }
}
