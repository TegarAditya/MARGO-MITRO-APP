<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Semester;
use App\Models\Productionperson;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\StockMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Excel;

use Alert;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Purchase::query()->select(sprintf('%s.*', (new Purchase())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $model = 'purchases';
                $show = true;
                $edit = true;
                $delete = false;

                return view('partials.actions', compact(
                    'model',
                    'show',
                    'edit',
                    'delete',
                    'row'
                ));
            });

            $table->editColumn('no_suratjalan', function ($row) {
                return $row->no_suratjalan ? $row->no_suratjalan : '';
            });

            $table->editColumn('no_spk', function ($row) {
                return $row->no_spk ? $row->no_spk : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->addColumn('subkontraktor', function ($row) {
                return $row->subkontraktor ? $row->subkontraktor->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->cover || $request->isi || $request->jenjang || $request->custom_price || $request->kelas || $request->semester) {
            $query = Product::with(['category', 'brand', 'isi', 'jenjang', 'semester']);
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

        $subkontraktors = Productionperson::where('type', 'finishing')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.purchases.create', compact('products', 'covers', 'isi', 'jenjang', 'semesters', 'subkontraktors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable',
            'no_suratjalan' => 'nullable',
            'productionperson_id' => 'required',
            'semester_id' => 'required'
        ]);

        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $semester = $request->semester;

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'no_suratjalan' => $request->no_suratjalan,
                'no_spk' => Purchase::generateNoSpk($request->semester_id),
                'date' => $request->date,
                'confirmed' => false,
                'note' => $request->note,
                'productionperson_id' => $request->productionperson_id,
                'semester_id' => $request->semester_id
            ]);

            if ($request->products) {
                $products = Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($purchase, $request) {
                    $qty = (int) $request->products[$item->id]['qty'] ?: 0;

                    $item->stock_movements()->create([
                        'reference' => $purchase->id,
                        'type' => 'purchase',
                        'quantity' => $qty,
                        'stock_awal' => $item->stock,
                        'stock_akhir' => $item->stock + $qty,
                        'product_id' => $item->id,
                        'date' => $request->date,
                    ]);
                    $item->update(['stock' => $item->stock + $qty ]);

                    $purchaseDetail = PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'productionperson_id' => $request->productionperson_id,
                        'semester_id' => $request->semester_id,
                        'product_id' => $item->id,
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

        return redirect()->route('admin.purchases.edit', ['purchase' => $purchase->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('details', 'semester', 'subkontraktor');

        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase, Request $request)
    {
        if ($request->cover || $request->isi || $request->jenjang || $request->custom_price || $request->kelas || $request->semester) {
            $query = Product::with(['category', 'brand', 'isi', 'jenjang', 'semester']);
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

        $subkontraktors = Productionperson::where('type', 'finishing')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.purchases.edit', compact('purchase', 'products', 'covers', 'isi', 'jenjang', 'semesters', 'subkontraktors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'date' => 'required|date',
            'note' => 'nullable',
            'no_suratjalan' => 'nullable',
            'productionperson_id' => 'required',
            'semester_id' => 'required'
        ]);

        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $semester = $request->semester;

        if ($request->filter) {
            $purchase->forceFill([
                'no_suratjalan' => $request->no_suratjalan,
                'date' => $request->date,
                'confirmed' => false,
                'note' => $request->note,
                'productionperson_id' => $request->productionperson_id,
                'semester_id' => $request->semester_id
            ])->save();

            return redirect()->route('admin.purchases.edit', ['purchase' => $purchase->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
        }

        $request->validate([
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $purchase->forceFill([
                'no_suratjalan' => $request->no_suratjalan,
                'date' => $request->date,
                'confirmed' => false,
                'note' => $request->note,
                'productionperson_id' => $request->productionperson_id,
                'semester_id' => $request->semester_id
            ])->save();

            foreach ($purchase->details as $detail) {
                if ($product = $detail->product) {
                    $product->update([
                        'stock' => $product->stock - $detail->quantity
                    ]);
                }
            }

            $purchase_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($purchase, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;

                $item->stock_movements()->updateOrCreate([
                    'reference' => $purchase->id,
                    'type' => 'purchase',
                    'product_id' => $item->id,
                ],[
                    'quantity' => $qty,
                    'stock_awal' => $item->stock,
                    'stock_akhir' => $item->stock + $qty,
                    'product_id' => $item->id,
                    'date' => $request->date,
                ]);
                $item->update(['stock' => $item->stock + $qty ]);

                return [
                    'purchase_id' => $purchase->id,
                    'productionperson_id' => $request->productionperson_id,
                    'semester_id' => $request->semester_id,
                    'product_id' => $item->id,
                    'quantity' => $qty,
                ];
            });

            foreach ($purchase_details as $detail) {
                $exists = $purchase->details->where('product_id', $detail['product_id'])->first() ?: new PurchaseDetail;

                $exists->forceFill($detail)->save();
            }

            // Delete items if removed
            $purchase->details()
                ->whereNotIn('product_id', $purchase_details->pluck('product_id'))
                ->forceDelete();

            StockMovement::where('reference', $purchase->id)
                ->where('type', 'purchase')
                ->whereNotIn('product_id', $purchase_details->pluck('product_id'))
                ->delete();

            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Something wrong !');
            return redirect()->back();
        }

        return redirect()->route('admin.purchases.edit', ['purchase' => $purchase->id, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'semester' => $semester]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
