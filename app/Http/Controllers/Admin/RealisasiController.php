<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\FinishingOrder;
use App\Models\FinishingOrderDetail;
use App\Models\Realisasi;
use App\Models\RealisasiDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Realisasi::with(['finishing_order'])->select(sprintf('%s.*', (new Realisasi())->getTable()));
            $table = Datatables::of($query);

            $table->addColumn('finishing_order', '&nbsp;');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_show';
                $editGate = 'production_order_edit';
                $deleteGate = 'production_order_delete';
                $crudRoutePart = 'realisasis';
                $parent = 'finishing-orders';
                $idParent = $row->finishing_order->id;

                return view('partials.datatableOrderActionsRealisasi', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'parent',
                'idParent',
                'row',
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('finishing_order', function ($row) {
                return !$row->finishing_order ? '' : $row->finishing_order->po_number;
            });
            $table->editColumn('no_realisasi', function ($row) {
                return $row->no_realisasi ? $row->no_realisasi : '';
            });
            $table->addColumn('date', function ($row) {
                return $row->date;
            });

            $table->editColumn('nominal', function ($row) {
                return $row->nominal ? $row->nominal : '';
            });

            $table->editColumn('lunas', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->lunas ? 'checked' : null) . '> '.($row->lunas ? 'Sudah' : 'Belum');
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'lunas']);

            return $table->make(true);
        }

        return view('admin.realisasis.index');
    }

    public function create()
    {
        $finishingOrders = FinishingOrder::get()->mapWithKeys(function($item) {
            return [$item->id => $item->po_number];
        })->prepend(trans('global.pleaseSelect'), '');
        $fo_details = FinishingOrderDetail::with(['product', 'product.media', 'product.category'])
            ->whereHas('product')
            ->get();

        $realisasi = new Realisasi();
        $realisasi_details = collect([]);
        $finishingOrder = new FinishingOrder();

        if ($finishing_order_id = request('finishing_order_id')) {
            $finishingOrder = FinishingOrder::with('realisasis')->findOrFail($finishing_order_id);
            $fo_details = $fo_details->where('finishing_order_id', $finishing_order_id);
        }

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.realisasis.create', compact('finishingOrders', 'fo_details', 'realisasi', 'finishingOrder', 'realisasi_details', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'finishing_order_id' => 'required|exists:finishing_orders,id',
            'products' => 'required|array|min:1',
        ]);

        $finishingOrder = FinishingOrder::findOrFail($request->finishing_order_id);

        DB::beginTransaction();
        try {
            $realisasi = Realisasi::create([
                'no_realisasi' => Realisasi::generateNoRealisasi(),
                'date' => $request->date,
                'nominal' => (float) $request->nominal,
                'finishing_order_id' => $request->finishing_order_id,
            ]);

            $products = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($realisasi, $finishingOrder, $request) {
                $qty = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                $item->stock_movements()->create([
                    'reference' => $realisasi->id,
                    'type' => 'realisasi',
                    'quantity' => $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock + $qty ]);

                $fo_detail = $finishingOrder->finishing_order_details()->where('product_id', $item->id)->first();

                if ($fo_detail) {
                    $fo_detail->update([
                        'prod_qty' => DB::raw("finishing_order_details.prod_qty + $qty"),
                    ]);
                }

                return [
                    'product_id' => $item->id,
                    'realisasi_id' => $realisasi->id,
                    'finishing_order_id' => $finishingOrder->id,
                    'fo_detail_id' => !$fo_detail ? null : $fo_detail->id,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ];
            });

            $realisasi->realisasi_details()->createMany($products->all());

            DB::commit();

            Alert::success('Success', 'Realisasi Finishing berhasil disimpan');

            if ($request->redirect) {
                return redirect($request->redirect);
            }

            return redirect()->route('admin.realisasis.edit', $realisasi->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Realisasi $realisasi)
    {
        $finishingOrders = FinishingOrder::get()->mapWithKeys(function($item) {
            return [$item->id => $item->po_number];
        })->prepend(trans('global.pleaseSelect'), '');
        $fo_details = FinishingOrderDetail::with(['product', 'product.media'])
            ->whereHas('product')
            ->get();

        $realisasi->load('realisasi_details', 'finishing_order', 'finishing_order.realisasis', 'finishing_order.realisasis.realisasi_details');

        $finishingOrder = null;
        if ($finishingOrder = $realisasi->finishing_order) {
            $fo_details = $fo_details->where('finishing_order_id', $finishingOrder->id);
        }

        $realisasi_details = $realisasi->realisasi_details->map(function($item) use ($fo_details) {
            $item->finishing_order_detail = $fo_details->where('product_id', $item->product_id)->first();

            return $item;
        });

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.realisasis.edit', compact('finishingOrders', 'fo_details', 'realisasi', 'finishingOrder', 'realisasi_details', 'categories', 'products'));
    }

    public function update(Request $request, Realisasi $realisasi)
    {
        $request->validate([
            'date' => 'required|date',
            'finishing_order_id' => 'required|exists:finishing_orders,id',
            'products' => 'required|array|min:1',
        ]);

        $finishingOrder = FinishingOrder::findOrFail($request->finishing_order_id);

        DB::beginTransaction();
        try {
            $realisasi->forceFill([
                'no_realisasi' => Realisasi::generateNoRealisasi(),
                'date' => $request->date,
                'nominal' => (float) $request->nominal,
                'finishing_order_id' => $request->finishing_order_id,
            ])->save();

            $realisasi->load([
                'realisasi_details', 'realisasi_details.product',
            ]);

            // Restore to previous data
            foreach ($realisasi->realisasi_details as $realisasi_detail) {
                if ($product = $realisasi_detail->product) {
                    $product->update([
                        'stock' => $product->stock - $realisasi_detail->qty
                    ]);
                }

                $finishingOrder->finishing_order_details()->where('product_id', $realisasi_detail->product_id)->update([
                    'prod_qty' => DB::raw("finishing_order_details.prod_qty - $realisasi_detail->qty"),
                ]);
            }

            // Update with new items
            $realisasi_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($realisasi, $finishingOrder, $request) {
                $qty = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                $item->stock_movements()->updateOrCreate([
                    'reference' => $realisasi->id,
                    'type' => 'realisasi',
                    'product_id' => $item->id,
                ],[
                    'reference' => $realisasi->id,
                    'type' => 'realisasi',
                    'quantity' => $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock + $qty ]);

                $fo_detail = $finishingOrder->finishing_order_details()->where('product_id', $item->id)->first();

                if ($fo_detail) {
                    $fo_detail->update([
                        'prod_qty' => DB::raw("finishing_order_details.prod_qty + $qty"),
                    ]);
                }

                return [
                    'product_id' => $item->id,
                    'realisasi_id' => $realisasi->id,
                    'finishing_order_id' => $finishingOrder->id,
                    'fo_detail_id' => !$fo_detail ? null : $fo_detail->id,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ];
            });

            foreach ($realisasi_details as $realisasi_detail) {
                $exists = $realisasi->realisasi_details->where('product_id', $realisasi_detail['product_id'])->first() ?: new RealisasiDetail();

                $exists->forceFill($realisasi_detail)->save();
            }

            // Delete items if removed
            $realisasi->realisasi_details()
                ->whereNotIn('product_id', $realisasi_details->pluck('product_id'))
                ->forceDelete();
            StockMovement::where('reference', $realisasi->id)
                ->where('type', 'realisasi')
                ->whereNotIn('product_id', $realisasi_details->pluck('product_id'))
                ->delete();

            DB::commit();

            Alert::success('Success', 'Realisasi Finishing berhasil disimpan');

            if ($request->redirect) {
                return redirect($request->redirect);
            }

            return redirect()->route('admin.realisasis.edit', $realisasi->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(Realisasi $realisasi)
    {
        $realisasi->load([
            'realisasi_details',
            'finishing_order', 'finishing_order.realisasis', 'finishing_order.realisasis.realisasi_details'
        ]);

        return view('admin.realisasis.show', compact('realisasi'));
    }

    public function destroy(Realisasi $realisasi)
    {
        $finishingOrder = FinishingOrder::findOrFail($realisasi->finishing_order_id);

        DB::beginTransaction();
        try {
            $realisasi->load([
                'realisasi_details', 'realisasi_details.product',
            ]);

            // Restore to previous data
            foreach ($realisasi->realisasi_details as $realisasi_detail) {
                if ($product = $realisasi_detail->product) {
                    $product->update([
                        'stock' => $product->stock - $realisasi_detail->qty
                    ]);
                }

                $finishingOrder->finishing_order_details()
                    ->where('product_id', $realisasi_detail->product_id)
                    ->update([
                        'prod_qty' => DB::raw("finishing_order_details.prod_qty - $realisasi_detail->qty"),
                    ]);
            }

            // Delete items if removed
            $realisasi->realisasi_details()
                ->whereIn('product_id', $realisasi->realisasi_details->pluck('product_id'))
                ->forceDelete();
            StockMovement::where('reference', $realisasi->id)
                ->where('type', 'realisasi')
                ->whereIn('product_id', $realisasi->realisasi_details->pluck('product_id'))
                ->delete();

            $realisasi->delete();

            DB::commit();

            Alert::success('Success', 'Realisasi Finishing berhasil dihapus');

            return redirect()->route('admin.realisasis.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }

        return back();
    }

    public function massDestroy(Request $request)
    {
        // Realisasi::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function setPaid(Request $request)
    {
        try {
            $realisasi = Realisasi::find($request->id);
            $realisasi->lunas = 1;
            $realisasi->save();
            return response()->json(['status' => 'success', 'message' => 'Kwitansi berhasil dibayar']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
