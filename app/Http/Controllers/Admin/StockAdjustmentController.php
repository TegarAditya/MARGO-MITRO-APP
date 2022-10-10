<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockAdjustmentRequest;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Http\Requests\UpdateStockAdjustmentRequest;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentDetail;
use App\Models\StockMovement;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Semester;
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
            $query = StockAdjustment::query()->select(sprintf('%s.*', (new StockAdjustment())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'stock_adjustment_show';
                $editGate = 'stock_adjustment_edit';
                $deleteGate = '';
                // $deleteGate = 'stock_adjustment_delete';
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
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.stockAdjustments.index');
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('stock_adjustment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->cover || $request->isi || $request->jenjang || $request->custom_price || $request->kelas || $request->semester) {
            $query = Product::with(['media', 'category', 'brand', 'isi', 'jenjang', 'semester']);
            if ($request->cover) {
                $query->where('brand_id', $request->cover);
            }
            if ($request->isi) {
                $query->where('isi_id', $request->isi);
            }
            if ($request->jenjang) {
                $query->where('jenjang_id', $request->jenjang);
            }
            if ($request->kelas) {
                $query->where('kelas_id', $request->kelas);
            }
            if ($request->semester) {
                $query->where('semester_id', $request->semester);
            }
            $products = $query->get();
        } else {
            $products = collect([]);
        }

        $covers = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $semesters = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockAdjustments.create', compact('products', 'isi', 'covers', 'jenjang', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'operation' => 'required',
            'note' => 'nullable',
        ]);

        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $semester = $request->semester;

        DB::beginTransaction();
        try {
            $stockAdjustment = StockAdjustment::create([
                'date' => $request->date,
                'operation' => $request->operation,
                'note' => $request->note
            ]);

            if ($request->products) {
                $multiplier = $stockAdjustment->is_increase ? 1 : -1;
                $products = Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($stockAdjustment, $request, $multiplier) {
                    $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                    $qty = $qty * $multiplier;

                    $item->stock_movements()->create([
                        'reference' => $stockAdjustment->id,
                        'type' => 'adjustment',
                        'quantity' => $qty,
                        'product_id' => $item->id,
                    ]);
                    $item->update(['stock' => $item->stock + $qty ]);

                    $adjustmentDetail = StockAdjustmentDetail::create([
                        'product_id' => $item->id,
                        'stock_adjustment_id' => $stockAdjustment->id,
                        'quantity' => $qty,
                    ]);
                });
            }

            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Something wrong !');
            return redirect()->back();
        }

        // Alert::success('Success', 'Stock Adjustment berhasil disimpan');
        return redirect()->route('admin.stock-adjustments.edit', ['stock_adjustment' => $stockAdjustment->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
    }

    public function edit(StockAdjustment $stockAdjustment, Request $request)
    {
        abort_if(Gate::denies('stock_adjustment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->cover || $request->isi || $request->jenjang || $request->custom_price || $request->kelas || $request->semester) {
            $query = Product::with(['media', 'category', 'brand', 'isi', 'jenjang', 'semester']);
            if ($request->cover) {
                $query->where('brand_id', $request->cover);
            }
            if ($request->isi) {
                $query->where('isi_id', $request->isi);
            }
            if ($request->jenjang) {
                $query->where('jenjang_id', $request->jenjang);
            }
            if ($request->kelas) {
                $query->where('kelas_id', $request->kelas);
            }
            if ($request->semester) {
                $query->where('semester_id', $request->semester);
            }
            $products = $query->get();
        } else {
            $products = collect([]);
        }

        $covers = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $semesters = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockAdjustments.edit', compact('products', 'stockAdjustment', 'isi', 'covers', 'jenjang', 'semesters'));
    }

    public function update(Request $request, StockAdjustment $stockAdjustment)
    {
        $request->validate([
            'date' => 'required|date',
            'operation' => 'required',
            'note' => 'nullable',
        ]);

        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $semester = $request->semester;

        if ($request->filter) {
            $stockAdjustment->forceFill([
                'date' => $request->date,
                'operation' => $request->operation,
                'note' => $request->note
            ])->save();

            return redirect()->route('admin.stock-adjustments.edit', ['stock_adjustment' => $stockAdjustment->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
        }

        $request->validate([
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $stockAdjustment->forceFill([
                'date' => $request->date,
                'operation' => $request->operation,
                'note' => $request->note
            ])->save();

            $multiplier = $stockAdjustment->is_increase ? 1 : -1;

            foreach ($stockAdjustment->details as $detail) {
                if ($product = $detail->product) {
                    $product->update([
                        'stock' => $product->stock - $detail->quantity
                    ]);
                }
            }

            $stockAdjustment_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($stockAdjustment, $request, $multiplier) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $qty = $qty * $multiplier;

                $item->stock_movements()->updateOrCreate([
                    'reference' => $stockAdjustment->id,
                    'type' => 'adjustment',
                    'product_id' => $item->id,
                ],[
                    'quantity' => $qty,
                ]);
                $item->update(['stock' => $item->stock + $qty ]);

                return [
                    'product_id' => $item->id,
                    'stock_adjustment_id' => $stockAdjustment->id,
                    'quantity' => $qty,
                ];
            });

            foreach ($stockAdjustment_details as $detail) {
                $exists = $stockAdjustment->details->where('product_id', $detail['product_id'])->first() ?: new StockAdjustmentDetail;

                $exists->forceFill($detail)->save();
            }

            // Delete items if removed
            $stockAdjustment->details()
                ->whereNotIn('product_id', $stockAdjustment_details->pluck('product_id'))
                ->forceDelete();
            StockMovement::where('reference', $stockAdjustment->id)
                ->where('type', 'adjustment')
                ->whereNotIn('product_id', $stockAdjustment_details->pluck('product_id'))
                ->delete();

            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Something wrong !');
            return redirect()->back();
        }

        Alert::success('Success', 'Stock Adjustment berhasil disimpan');

        return redirect()->route('admin.stock-adjustments.index');
        // return redirect()->route('admin.stock-adjustments.edit', ['stock_adjustment' => $stockAdjustment->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        abort_if(Gate::denies('stock_adjustment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockAdjustment->load('details');

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
