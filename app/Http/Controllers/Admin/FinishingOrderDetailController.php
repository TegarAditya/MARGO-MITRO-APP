<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFinishingOrderDetailRequest;
use App\Http\Requests\StoreFinishingOrderDetailRequest;
use App\Http\Requests\UpdateFinishingOrderDetailRequest;
use App\Models\Product;
use App\Models\FinishingOrder;
use App\Models\FinishingOrderDetail;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FinishingOrderDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('production_order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FinishingOrderDetail::with(['finishing_order', 'product'])->select(sprintf('%s.*', (new FinishingOrderDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_detail_show';
                $editGate = 'production_order_detail_edit';
                $deleteGate = 'production_order_detail_delete';
                $crudRoutePart = 'finishing-order-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('finishing_order_po_number', function ($row) {
                return $row->finishing_order ? $row->finishing_order->po_number : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('order_qty', function ($row) {
                return $row->order_qty ? $row->order_qty : '';
            });
            $table->editColumn('prod_qty', function ($row) {
                return $row->prod_qty ? $row->prod_qty : '';
            });
            $table->editColumn('ongkos_satuan', function ($row) {
                return $row->ongkos_satuan ? $row->ongkos_satuan : '';
            });
            $table->editColumn('ongkos_total', function ($row) {
                return $row->ongkos_total ? $row->ongkos_total : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'finishing_order', 'product']);

            return $table->make(true);
        }

        return view('admin.finishingOrderDetails.index');
    }

    public function create()
    {
        abort_if(Gate::denies('production_order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishing_orders = FinishingOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.finishingOrderDetails.create', compact('finishing_orders', 'products'));
    }

    public function store(StoreFinishingOrderDetailRequest $request)
    {
        $finishingOrderDetail = FinishingOrderDetail::create($request->all());

        return redirect()->route('admin.finishing-order-details.index');
    }

    public function edit(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishing_orders = FinishingOrder::pluck('po_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $finishingOrderDetail->load('finishing_order', 'product');

        return view('admin.finishingOrderDetails.edit', compact('finishingOrderDetail', 'finishing_orders', 'products'));
    }

    public function update(UpdateFinishingOrderDetailRequest $request, FinishingOrderDetail $finishingOrderDetail)
    {
        $finishingOrderDetail->update($request->all());

        return redirect()->route('admin.finishing-order-details.index');
    }

    public function show(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrderDetail->load('finishing_order', 'product');

        return view('admin.finishingOrderDetails.show', compact('finishingOrderDetail'));
    }

    public function destroy(FinishingOrderDetail $finishingOrderDetail)
    {
        abort_if(Gate::denies('production_order_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrderDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinishingOrderDetailRequest $request)
    {
        FinishingOrderDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
