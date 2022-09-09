<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockAdjustmentRequest;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Http\Requests\UpdateStockAdjustmentRequest;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use App\Models\Brand;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Excel;
use App\Imports\StockAdjustmentImport;
use Alert;

class StockAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('stock_adjustment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = StockAdjustment::with(['product'])->select(sprintf('%s.*', (new StockAdjustment())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'stock_adjustment_show';
                // $editGate = 'stock_adjustment_edit';
                $editGate = 'stock_adjustment_random';
                $deleteGate = 'stock_adjustment_delete';
                $crudRoutePart = 'stock-adjustments';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('operation', function ($row) {
                return $row->operation ? StockAdjustment::OPERATION_SELECT[$row->operation] : '';
            });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->nama_isi_buku : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        return view('admin.stockAdjustments.index');
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('stock_adjustment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->cover || $request->isi || $request->jenjang || $request->semester) {
            $query = Product::with(['brand', 'isi', 'jenjang']);
            if ($request->cover) {
                $query->where('brand_id', $request->cover);
            }
            if ($request->isi) {
                $query->where('isi_id', $request->isi);
            }
            if ($request->jenjang) {
                $query->where('jenjang_id', $request->jenjang);
            }
            if ($request->semester) {
                $query->where('semester', $request->semester);
            }
            $products = $query->get()->pluck('nama_isi_buku', 'id')->prepend(trans('global.pleaseSelect'), '');

        } else {
            $products = collect([]);
        }

        $covers = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockAdjustments.create', compact('products', 'isi', 'covers', 'jenjang'));
    }

    public function store(StoreStockAdjustmentRequest $request)
    {
        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $semester = $request->semester;

        DB::beginTransaction();
        try {
            $stockAdjustment = StockAdjustment::create($request->all());

            StockMovement::create([
                'reference' => $stockAdjustment->id,
                'type' => 'adjustment',
                'product_id' => $request->product_id,
                'quantity' => $stockAdjustment->is_increase ? $request->quantity : (-1 * $request->quantity)
            ]);

            $product = Product::find($request->product_id);

            if ($stockAdjustment->is_increase) {
                $newStock = $product->stock + (int) $request->quantity;
            } else {
                $newStock = $product->stock - (int) $request->quantity;
            }

            $product->update(['stock' => $newStock]);

            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Something wrong !');
            return redirect()->back();
        }

        return redirect()->route('admin.stock-adjustments.create', ['cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
    }

    public function edit(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stockAdjustment->load('product');

        return view('admin.stockAdjustments.edit', compact('products', 'stockAdjustment'));
    }

    public function update(UpdateStockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->update($request->all());

        return redirect()->route('admin.stock-adjustments.index');
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustment->load('product');

        return view('admin.stockAdjustments.show', compact('stockAdjustment'));
    }

    public function destroy(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {
            $product = Product::find($stockAdjustment->product_id);
            if ($stockAdjustment->is_increase) {
                $newStock = $product->stock - (int) $stockAdjustment->quantity;
            } else {
                $newStock = $product->stock + (int) $stockAdjustment->quantity;
            }
            $product->update(['stock' => $newStock]);

            $stockMovement = StockMovement::where('reference', $stockAdjustment->id)->where('type', 'adjustment')->first();
            $stockMovement->delete();

            $stockAdjustment->delete();

            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Something wrong !');
            return redirect()->back();
        }

        return back();
    }

    public function massDestroy(MassDestroyStockAdjustmentRequest $request)
    {
        StockAdjustment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file');
        $request->validate([
            'import_file' => 'mimes:csv,txt,xls,xlsx',
        ]);

        Excel::import(new StockAdjustmentImport(), $file);

        Alert::success('Success', 'Stock Adjustment berhasil di import');
        return redirect()->back();
    }
}
