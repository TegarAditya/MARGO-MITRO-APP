<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionOrderRequest;
use App\Http\Requests\StoreProductionOrderRequest;
use App\Http\Requests\UpdateProductionOrderRequest;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionOrderController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = $request->user();

        $query = ProductionOrder::with(['productionperson', 'created_by']);

        if ($user && $productionperson = $user->productionperson) {
            $query->where('productionperson_id', $productionperson->id);
        }

        $productionOrders = $query->get();

        return view('frontend.productionOrders.index', compact('productionOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.productionOrders.create', compact('productionpeople'));
    }

    public function store(StoreProductionOrderRequest $request)
    {
        $productionOrder = ProductionOrder::create($request->all());

        return redirect()->route('frontend.production-orders.index');
    }

    public function edit(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productionOrder->load('productionperson', 'created_by');

        return view('frontend.productionOrders.edit', compact('productionOrder', 'productionpeople'));
    }

    public function update(UpdateProductionOrderRequest $request, ProductionOrder $productionOrder)
    {
        $productionOrder->update($request->all());

        return redirect()->route('frontend.production-orders.index');
    }

    public function show(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->load('productionperson', 'created_by');

        return view('frontend.productionOrders.show', compact('productionOrder'));
    }

    public function process(Request $request, Int $id)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = $request->user();
        $productionperson = $user->productionperson;

        $productionOrder = ProductionOrder::with(['productionperson', 'created_by'])
            ->where('productionperson_id', $productionperson->id ?? -1)
            ->findOrFail($id);

        if ($productionOrder->status === ProductionOrder::STATUS_PENDING) {
            $productionOrder->update([ 'status' => ProductionOrder::STATUS_CHECKING ]);
        }

        return view('frontend.productionOrders.process', compact('productionOrder'));
    }

    public function processSubmit(Request $request, Int $id)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = $request->user();
        $productionperson = $user->productionperson;

        $productionOrder = ProductionOrder::with([
                'productionperson', 'created_by',
                'production_order_details',
            ])
            ->where('productionperson_id', $productionperson->id ?? -1)
            ->findOrFail($id);

        $products = $request->product ?: [];

        $productionOrder->production_order_details()
            ->whereNotIn('product_id', array_keys($products))
            ->update([
                'file' => 0,
                'plate' => 0,
                'plate_ambil' => 0,
            ]);

        $upserts = [];

        foreach ($productionOrder->production_order_details as $detail) {
            if (!isset($products[$detail->product_id])) {
                continue;
            }

            $product = $products[$detail->product_id];

            $upserts[] = [
                'id' => $detail->id,
                'file' => isset($product['file']) ? 1 : 0,
                'plate' => isset($product['plate']) ? 1 : 0,
                'plate_ambil' => isset($product['plate_ambil']) ? 1 : 0,
            ];
        }

        ProductionOrderDetail::upsert($upserts, ['id']);

        return redirect()->route('frontend.production-orders.process', $productionOrder->id);
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        abort_if(Gate::denies('production_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductionOrderRequest $request)
    {
        ProductionOrder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
