<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPreorderRequest;
use App\Http\Requests\StorePreorderRequest;
use App\Http\Requests\UpdatePreorderRequest;
use App\Models\Category;
use App\Models\Preorder;
use App\Models\PreorderDetail;
use App\Models\Product;
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

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($preorder, $request) {
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

                if ($exists->is_check != $order_detail['is_check'] && $order_detail['is_check'] == 1) {
                    $product_stocks->put($order_detail['product_id'], $order_detail['order_qty']);
                }

                $exists->forceFill($order_detail)->save();
            }

            if ($product_stocks->count()) {
                $upsert_products = $product_stocks->map(function($item, $key) use ($products) {
                    $product = $products->find($key);

                    return [
                        'id' => $key,
                        'stock' => !$product ? $item : ($product->stock + $item),
                    ];
                });

                $upsert_stocks = $product_stocks->map(function($item, $key) use ($preorder) {
                    return [
                        'id' => null,
                        'product_id' => $key,
                        'reference' => $preorder->id,
                        'type' => 'production_order',
                        'quantity' => $item,
                    ];
                });

                Product::upsert($upsert_products->all(), ['id']);
                StockMovement::upsert($upsert_stocks->all(), ['id']);
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
