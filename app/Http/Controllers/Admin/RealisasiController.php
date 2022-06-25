<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\Realisasi;
use App\Models\RealisasiDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Realisasi::with(['production_order'])->select(sprintf('%s.*', (new Realisasi())->getTable()));
            $table = Datatables::of($query);

            $table->addColumn('production_order', '&nbsp;');
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_show';
                $editGate = 'production_order_edit';
                $deleteGate = 'production_order_delete';
                $crudRoutePart = 'realisasis';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('production_order', function ($row) {
                return !$row->production_order ? '' : $row->production_order->po_number;
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

            $table->rawColumns(['actions', 'placeholder', 'order']);

            return $table->make(true);
        }

        return view('admin.realisasis.index');
    }

    public function create()
    {
        $productionOrders = ProductionOrder::get()->mapWithKeys(function($item) {
            return [$item->id => $item->po_number];
        })->prepend(trans('global.pleaseSelect'), '');
        $po_details = ProductionOrderDetail::with(['product', 'product.media', 'product.category'])
            ->whereHas('product')
            ->get();

        $realisasi = new Realisasi();
        $realisasi_details = collect([]);
        $productionOrder = new ProductionOrder();

        if ($production_order_id = request('production_order_id')) {
            $productionOrder = ProductionOrder::with('realisasis')->findOrFail($production_order_id);
            $po_details = $po_details->where('production_order_id', $production_order_id);
        }

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.realisasis.create', compact('productionOrders', 'po_details', 'realisasi', 'productionOrder', 'realisasi_details', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'production_order_id' => 'required|exists:production_orders,id',
            'products' => 'required|array|min:1',
        ]);

        $productionOrder = ProductionOrder::findOrFail($request->production_order_id);

        DB::beginTransaction();
        try {
            $realisasi = Realisasi::create([
                'no_realisasi' => Realisasi::generateNoRealisasi(),
                'date' => $request->date,
                'nominal' => (float) $request->nominal,
                'production_order_id' => $request->production_order_id,
            ]);

            $products = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($realisasi, $productionOrder, $request) {
                $qty = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                $item->stock_movements()->create([
                    'reference' => $realisasi->id,
                    'type' => 'realisasi',
                    'quantity' => $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock + $qty ]);

                $po_detail = $productionOrder->production_order_details()->where('product_id', $item->id)->first();
                
                if ($po_detail) {
                    $po_detail->update([
                        'prod_qty' => DB::raw("production_order_details.prod_qty + $qty"),
                    ]);
                }

                return [
                    'product_id' => $item->id,
                    'realisasi_id' => $realisasi->id,
                    'production_order_id' => $productionOrder->id,
                    'po_detail_id' => !$po_detail ? null : $po_detail->id,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $qty * $price,
                ];
            });

            $realisasi->realisasi_details()->createMany($products->all());

            DB::commit();

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
        $productionOrders = ProductionOrder::get()->mapWithKeys(function($item) {
            return [$item->id => $item->po_number];
        })->prepend(trans('global.pleaseSelect'), '');
        $po_details = ProductionOrderDetail::with(['product', 'product.media'])
            ->whereHas('product')
            ->get();

        $realisasi->load('realisasi_details', 'production_order', 'production_order.realisasis', 'production_order.realisasis.realisasi_details');

        $productionOrder = null;
        if ($productionOrder = $realisasi->production_order) {
            $po_details = $po_details->where('production_order_id', $productionOrder->id);
        }

        $realisasi_details = $realisasi->realisasi_details->map(function($item) use ($po_details) {
            $item->production_order_detail = $po_details->where('product_id', $item->product_id)->first();

            return $item;
        });

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.realisasis.edit', compact('productionOrders', 'po_details', 'realisasi', 'productionOrder', 'realisasi_details', 'categories', 'products'));
    }

    public function update(Request $request, Realisasi $realisasi)
    {
        $request->validate([
            'date' => 'required|date',
            'production_order_id' => 'required|exists:production_orders,id',
            'products' => 'required|array|min:1',
        ]);

        $productionOrder = ProductionOrder::findOrFail($request->production_order_id);

        DB::beginTransaction();
        try {
            $realisasi->forceFill([
                'no_realisasi' => Realisasi::generateNoRealisasi(),
                'date' => $request->date,
                'nominal' => (float) $request->nominal,
                'production_order_id' => $request->production_order_id,
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

                $productionOrder->production_order_details()->where('product_id', $realisasi_detail->product_id)->update([
                    'prod_qty' => DB::raw("production_order_details.prod_qty - $realisasi_detail->qty"),
                ]);
            }

            // Update with new items
            $realisasi_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($realisasi, $productionOrder, $request) {
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

                $po_detail = $productionOrder->production_order_details()->where('product_id', $item->id)->first();
                
                if ($po_detail) {
                    $po_detail->update([
                        'prod_qty' => DB::raw("production_order_details.prod_qty + $qty"),
                    ]);
                }

                return [
                    'product_id' => $item->id,
                    'realisasi_id' => $realisasi->id,
                    'production_order_id' => $productionOrder->id,
                    'po_detail_id' => !$po_detail ? null : $po_detail->id,
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
            'production_order', 'production_order.realisasis', 'production_order.realisasis.realisasi_details'
        ]);

        return view('admin.realisasis.show', compact('realisasi'));
    }

    public function destroy(Realisasi $realisasi)
    {
        $productionOrder = ProductionOrder::findOrFail($realisasi->production_order_id);

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

                $productionOrder->production_order_details()
                    ->where('product_id', $realisasi_detail->product_id)
                    ->update([
                        'prod_qty' => DB::raw("production_order_details.prod_qty - $realisasi_detail->qty"),
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
}
