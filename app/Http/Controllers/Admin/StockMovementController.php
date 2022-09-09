<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Unit;
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
            $query = StockMovement::with(['product']);

            if (!empty($request->brand)) {
                $brand = $request->brand;
                $query->whereHas('product', function($q) use($brand) {
                    $q->where('brand_id', $brand);
                });
            }

            if (!empty($request->jenjang)) {
                $jenjang = $request->jenjang;
                $query->whereHas('product', function($q) use($jenjang) {
                    $q->where('jenjang_id', $jenjang);
                });
            }

            if (!empty($request->kelas)) {
                $kelas = $request->kelas;
                $query->whereHas('product', function($q) use($kelas) {
                    $q->where('kelas_id', $kelas);
                });
            }

            if (!empty($request->halaman)) {
                $halaman = $request->halaman;
                $query->whereHas('product', function($q) use($halaman) {
                    $q->where('halaman_id', $halaman);
                });
            }

            if (!empty($request->isi)) {
                $isi = $request->isi;
                $query->whereHas('product', function($q) use($isi) {
                    $q->where('isi_id', $isi);
                });
            }

            if (!empty($request->semester)) {
                $semester = $request->semester;
                $query->whereHas('product', function($q) use($semester) {
                    $q->where('semester', $semester);
                });
            }
            $query->select(sprintf('%s.*', (new StockMovement())->table));
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
                    return 'Adjustment <a class="px-1" title="Reference" href="'.route('admin.stock-adjustments.show', $row->reference).'"><i class="fas fa-eye text-success  fa-lg"></i></a>';
                } else if ($row->type == 'invoice') {
                    return 'Invoice <a class="px-1" title="Reference" href="'.route('admin.invoices.show', $row->reference).'"><i class="fas fa-eye text-success  fa-lg"></i></a>';
                } else if ($row->type == 'realisasi') {
                    return 'Realisasi <a class="px-1" title="Reference" href="'.route('admin.realisasis.show', $row->reference).'"><i class="fas fa-eye text-success  fa-lg"></i></a>';
                }
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? StockMovement::TYPE_SELECT[$row->type] : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->nama_isi_buku : '';
            });

            $table->addColumn('sales', function ($row) {
                if ($row->type == 'adjustment') {
                    return $row->referensi ? StockAdjustment::OPERATION_SELECT[$row->referensi->operation] : '';
                } else if ($row->type == 'invoice') {
                    return $row->referensi ? $row->referensi->order->salesperson->name : '';
                } else if ($row->type == 'realisasi') {
                    return $row->referensi ? $row->referensi->production_order->productionperson->name : '';
                }
                return $row->referensi ? $row->referensi : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'reference']);

            return $table->make(true);
        }

        $brands = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kelas = Category::where('type', 'kelas')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $halaman = Category::where('type', 'halaman')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.stockMovements.index', compact('brands', 'jenjang', 'kelas', 'halaman', 'isi'));
    }
}
