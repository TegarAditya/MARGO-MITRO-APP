<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with(['salesperson'])->select(sprintf('%s.*', (new Order())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = 'order_edit';
                $deleteGate = 'order_delete';
                $crudRoutePart = 'orders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('no_order', function ($row) {
                return $row->no_order ? $row->no_order : '';
            });

            $table->addColumn('salesperson_name', function ($row) {
                return $row->salesperson ? $row->salesperson->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'salesperson']);

            return $table->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $products = Product::with(['media', 'category'])->get();

        return view('admin.orders.create', compact('salespeople', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $request->validate([
            'date' => 'required|date',
            'salesperson_id' => 'required|exists:salespeople,id',
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'no_order' => Order::generateNoOrder(),
                'date' => $request->date,
                'salesperson_id' => $request->salesperson_id,
            ]);

            $products = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($order, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;
                $unit_price = $item->price;

                $item->stock_movements()->create([
                    'reference' => $order->id,
                    'type' => 'order',
                    'quantity' => -1 * $qty,
                    'product_id' => $item->id,
                ]);
                $item->update([ 'stock' => $item->stock - $qty ]);

                return [
                    'product_id' => $item->id,
                    'order_id' => $order->id,
                    'quantity' => -1 * $qty,
                    'unit_price' => $unit_price,
                    'price' => $price,
                    'total' => $qty * $price,
                ];
            });

            $order->order_details()->createMany($products->all());

            $tagihan = $order->tagihan()->create([
                'order_id' => $order->id,
                'salesperson_id' => $order->salesperson_id,
                'total' => $products->sum('total'),
                'saldo' => $products->sum('total'),
            ]);
            $tagihan->tagihan_movements()->create([
                'reference' => $order->id,
                'type' => 'order',
                'nominal' => $products->sum('total'),
            ]);

            DB::commit();

            return redirect()->route('admin.orders.edit', $order->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $products = Product::with(['media', 'category'])->get();

        $order->load([
            'salesperson',
            'order_details',
            'order_details.product',
            'tagihan',
            'pembayarans',
            'invoices',
        ]);

        return view('admin.orders.edit', compact('order', 'salespeople', 'products'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('salesperson');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        Order::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
