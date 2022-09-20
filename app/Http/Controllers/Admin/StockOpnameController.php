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
use App\Models\Semester;
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
            $query = Product::with(['category', 'brand', 'unit', 'semester'])->where('stock','>', 0)->orderBy('stock', 'DESC')->select(sprintf('%s.*', (new Product())->table));
            if (!empty($request->name)) {
                $query->where('name','LIKE','%'.$request->name.'%');
            }

            if (!empty($request->brand)) {
                $query->where('brand_id', $request->brand);
            }

            if (!empty($request->jenjang)) {
                $query->where('jenjang_id', $request->jenjang);
            }

            if (!empty($request->kelas)) {
                $query->where('kelas_id', $request->kelas);
            }

            if (!empty($request->halaman)) {
                $query->where('halaman_id', $request->halaman);
            }

            if (!empty($request->isi)) {
                $query->where('isi_id', $request->isi);
            }

            if (!empty($request->semester)) {
                $query->where('semester_id', $request->semester);
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('value', '&nbsp;');

            $table->editColumn('name', function ($row) {
                return '<a href="'.route('admin.products.show', $row->id).'">'.$row->nama_isi_buku .'</a>';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
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

            return $table->make(true);
        }

        $brands = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kelas = Category::where('type', 'kelas')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $halaman = Category::where('type', 'halaman')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $semester = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockOpnames.index', compact('brands', 'jenjang', 'kelas', 'halaman', 'isi', 'semester'));
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
