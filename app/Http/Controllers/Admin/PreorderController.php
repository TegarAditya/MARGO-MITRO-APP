<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPreorderRequest;
use App\Http\Requests\StorePreorderRequest;
use App\Http\Requests\UpdatePreorderRequest;
use App\Models\Category;
use App\Models\HistoryProduction;
use App\Models\Preorder;
use App\Models\PreorderDetail;
use App\Models\Product;
use App\Models\SummaryOrder;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PreorderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('preorder_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Preorder::query()->select(sprintf('%s.*', (new Preorder())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'preorder_show';
                $editGate = 'preorder_edit';
                $deleteGate = 'preorder_delete';
                $crudRoutePart = 'preorders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('no_preorder', function ($row) {
                return $row->no_preorder ? $row->no_preorder : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.preorders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('preorder_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();

        return view('admin.preorders.create', compact('categories'));
    }

    public function store(StorePreorderRequest $request)
    {
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            // 'bahans' => 'required|array|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $preorder = new Preorder();

            $preorder->forceFill([
                'no_preorder' => Preorder::generateNoPO(),
                'date' => $request->date,
                'note' => $request->note,
                'created_by' => $user->id,
            ])->save();

            $products = Product::whereIn('id', array_keys($request->products))->get();
            
            $order_details = $products->map(function($item) use ($preorder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $group = (int) $request->products[$item->id]['group'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'preorder_detail_id' => $preorder->id,
                    'quantity' => $qty,
                    'group' => $group,
                ];
            });

            $preorder->preorder_details()->createMany($order_details->all());
            $preorder->load('preorder_details');

            if ($preorder->preorder_details->count()) {
                $summary_query = SummaryOrder::query()
                    ->whereIn('product_id', $preorder->preorder_details->pluck('product_id'));
                    // ->where('preorder_id', $preorder->id);

                $summary_orders = $summary_query->get();

                $upsert_summary_orders = $preorder->preorder_details->map(function(PreorderDetail $item) use ($summary_orders, $preorder) {
                    $summary = $summary_orders->where('product_id', $item->product_id)
                        // ->where('preorder_id', $preorder->id)
                        ->first();

                    return [
                        'id' => $summary->id ?? null,
                        'type' => 'preorder',
                        'category_id' => null,
                        'quantity' => $item->quantity + ($summary->quantity ?? 0),
                        'preorder_id' => null, // $preorder->id,
                        'order_id' => null,
                        'product_id' => $item->product_id,
                    ];
                });

                SummaryOrder::upsert($upsert_summary_orders->all(), ['id']);

                $upsert_history_productions = $summary_query->get()->map(function(SummaryOrder $item) use ($preorder) {
                    $preorder_detail = $preorder->preorder_details->where('product_id', $item->product_id)->first();

                    return [
                        'id' => null,
                        'type' => "preorder_detail",
                        'reference_id' => $preorder_detail->id ?? null,
                        'quantity' => $preorder_detail->quantity ?? null,
                        'summary_order_id' => $item->id,
                        'product_id' => $item->product_id,
                    ];
                });

                HistoryProduction::upsert($upsert_history_productions->all(), ['id']);
            }

            DB::commit();

            Alert::success('Success', 'Preorder berhasil di simpan');

            return redirect()->route('admin.preorders.edit', $preorder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $preorder->load([
            'preorder_details'
        ]);

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();

        return view('admin.preorders.edit', compact('preorder', 'categories'));
    }

    public function update(UpdatePreorderRequest $request, Preorder $preorder)
    {
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $preorder->forceFill([
                'date' => $request->date,
                'note' => $request->note,
            ])->save();
            $preorder->load('preorder_details');

            $products = Product::whereIn('id', array_keys($request->products))->get();
            $order_details = $products->map(function($item) use ($preorder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $group = (int) $request->products[$item->id]['group'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'preorder_detail_id' => $preorder->id,
                    'quantity' => $qty,
                    'group' => $group,
                ];
            });

            $product_stocks = collect([]);

            foreach ($order_details as $order_detail) {
                $exists = $preorder->preorder_details()->where('product_id', $order_detail['product_id'])->first() ?: new PreorderDetail();

                if ($exists->id) {
                    $product_stocks->put($order_detail['product_id'], [
                        'preorder_detail_id' => $exists->id,
                        'quantity' => $order_detail['quantity'],
                        'quantity_old' => $exists->quantity ?: 0,
                    ]);
                }

                $exists->forceFill($order_detail)->save();
            }

            if ($product_stocks->count()) {
                $summary_query = SummaryOrder::query()
                    ->whereIn('product_id', $product_stocks->keys());
                    // ->where('preorder_id', $preorder->id);

                $summary_orders = $summary_query->get();

                $upsert_summary_orders = $product_stocks->map(function($item, $key) use ($summary_orders) {
                    $summary = $summary_orders->where('product_id', $key)->first();

                    return [
                        'id' => $summary->id ?? null,
                        'type' => 'preorder',
                        'category_id' => null,
                        'quantity' => $item['quantity'] + ($summary->quantity ?? 0) - $item['quantity_old'],
                        'preorder_id' => null, // $preorder->id,
                        'order_id' => null,
                        'product_id' => $key,
                    ];
                });

                $summary_orders = $summary_query->get();
                $histories = HistoryProduction::whereIn('product_id', $product_stocks->keys())
                    ->whereIn('reference_id', $product_stocks->pluck('preorder_detail_id'))
                    ->whereIn('summary_order_id', $summary_orders->pluck('id'))
                    ->where('type', 'preorder_details')
                    ->get();

                $upsert_history_productions = $summary_orders->map(function(SummaryOrder $item) use ($preorder, $product_stocks, $histories) {
                    $stock = $product_stocks->get($item->product_id, []);
                    $preorder_detail = $preorder->preorder_details
                        ->where('product_id', $item->product_id)
                        ->first();
                    $history = $histories->where('product_id', $item->product_id)
                        ->where('summary_order_id', $item->id)
                        ->first();

                    return [
                        'id' => $history->id ?? null,
                        'type' => "preorder_details",
                        'reference_id' => $preorder_detail->id ?? null,
                        'quantity' => $stock['quantity'],
                        'summary_order_id' => $item->id,
                        'product_id' => $item->product_id,
                    ];
                });

                SummaryOrder::upsert($upsert_summary_orders->all(), ['id']);
                HistoryProduction::upsert($upsert_history_productions->all(), ['id']);
            }

            // Delete items if removed
            $preorder->preorder_details()
                ->whereNotIn('product_id', $order_details->pluck('product_id'))
                ->forceDelete();

            DB::commit();

            Alert::success('Success', 'Production Order berhasil di simpan');

            return redirect()->route('admin.preorders.edit', $preorder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.preorders.show', compact('preorder'));
    }

    public function destroy(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $preorder->delete();

        return back();
    }

    public function massDestroy(MassDestroyPreorderRequest $request)
    {
        Preorder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
