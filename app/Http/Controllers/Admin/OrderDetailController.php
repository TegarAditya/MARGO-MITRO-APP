<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderDetailRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = OrderDetail::with(['order', 'product'])->select(sprintf('%s.*', (new OrderDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_detail_show';
                $editGate = 'order_detail_edit';
                $deleteGate = 'order_detail_delete';
                $crudRoutePart = 'order-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('order_date', function ($row) {
                return $row->order ? $row->order->date : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('unit_price', function ($row) {
                return $row->unit_price ? $row->unit_price : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'product']);

            return $table->make(true);
        }

        return view('admin.orderDetails.index');
    }

    public function create()
    {
        abort_if(Gate::denies('order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orderDetails.create', compact('orders', 'products'));
    }

    public function store(StoreOrderDetailRequest $request)
    {
        $orderDetail = OrderDetail::create($request->all());

        return redirect()->route('admin.order-details.index');
    }
}
