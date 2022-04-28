<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockOpnameRequest;
use App\Http\Requests\StoreStockOpnameRequest;
use App\Http\Requests\UpdateStockOpnameRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('stock_opname_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Product::with(['category', 'brand', 'unit'])->select(sprintf('%s.*', (new Product())->table))->where('stock','>', 0)->orderBy('stock', 'DESC');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('value', '&nbsp;');

            $table->editColumn('name', function ($row) {
                return '<a href="'.route('admin.products.show', $row->id).'">'.$row->name .'</a>';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('brand_name', function ($row) {
                return $row->brand ? $row->brand->name : '';
            });
            $table->editColumn('hpp', function ($row) {
                return $row->hpp ? 'Rp '. number_format($row->hpp, 0, ',', '.') : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? 'Rp '. number_format($row->price, 0, ',', '.') : '';
            });
            $table->editColumn('stock', function ($row) {
                return $row->stock ? $row->stock. ' '. $row->unit->name : '';
            });
            $table->editColumn('value', function ($row) {
                return 'Purchase: Rp'. number_format(($row->stock * $row->hpp), 0, ',', '.') .'<br>Sales: Rp'.number_format(($row->stock * $row->price), 0, ',', '.');
            });
            $table->rawColumns(['placeholder', 'category', 'brand', 'value', 'name']);

            $table->with('total_stock', function() use ($query) {
                return $query->sum('stock');
            });

            return $table->make(true);
        }

        $categories = Category::get();
        $brands     = Brand::get();

        return view('admin.stockOpnames.index', compact('categories', 'brands'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_opname_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.stockOpnames.create');
    }

    public function store(StoreStockOpnameRequest $request)
    {
        $stockOpname = StockOpname::create($request->all());

        return redirect()->route('admin.stock-opnames.index');
    }

    public function edit(StockOpname $stockOpname)
    {
        abort_if(Gate::denies('stock_opname_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.stockOpnames.edit', compact('stockOpname'));
    }

    public function update(UpdateStockOpnameRequest $request, StockOpname $stockOpname)
    {
        $stockOpname->update($request->all());

        return redirect()->route('admin.stock-opnames.index');
    }

    public function show(StockOpname $stockOpname)
    {
        abort_if(Gate::denies('stock_opname_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.stockOpnames.show', compact('stockOpname'));
    }

    public function destroy(StockOpname $stockOpname)
    {
        abort_if(Gate::denies('stock_opname_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockOpname->delete();

        return back();
    }

    public function massDestroy(MassDestroyStockOpnameRequest $request)
    {
        StockOpname::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
