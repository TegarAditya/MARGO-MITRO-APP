<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderDetailRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderDetails = OrderDetail::with(['order', 'product'])->get();

        return view('frontend.orderDetails.index', compact('orderDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.orderDetails.create', compact('orders', 'products'));
    }

    public function store(StoreOrderDetailRequest $request)
    {
        $orderDetail = OrderDetail::create($request->all());

        return redirect()->route('frontend.order-details.index');
    }
}
