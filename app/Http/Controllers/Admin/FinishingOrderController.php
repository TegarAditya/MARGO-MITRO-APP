<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFinishingOrderRequest;
use App\Http\Requests\StoreFinishingOrderRequest;
use App\Http\Requests\UpdateFinishingOrderRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\FinishingOrder;
use App\Models\FinishingOrderDetail;
use App\Models\Productionperson;
use App\Models\Order;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use App\Models\ProductionOrder;
use Illuminate\Support\Facades\Date;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use NumberFormatter;

class FinishingOrderController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('production_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FinishingOrder::with(['productionperson', 'created_by'])->select(sprintf('%s.*', (new FinishingOrder())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'production_order_show';
                $editGate = 'production_order_edit';
                $deleteGate = 'production_order_delete_hidden'; //order delete hidden
                $crudRoutePart = 'finishing-orders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('po_number', function ($row) {
                return $row->po_number ? $row->po_number : '';
            });
            $table->editColumn('no_spk', function ($row) {
                return $row->no_spk ? $row->no_spk : '';
            });
            $table->addColumn('productionperson_name', function ($row) {
                return $row->productionperson ? $row->productionperson->name : '';
            });

            $table->addColumn('created_by_name', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'productionperson', 'created_by']);

            return $table->make(true);
        }

        return view('admin.finishingOrders.index');
    }

    public function dashboard(Request $request)
    {
        if ($request->input('date')) {
            $dates = explode(' - ', $request->input('date', ' - '));
            $start_at = Date::parse($dates[0] ?: 'first day of this month')->startOf('day');
            $end_at = Date::parse($dates[1] ?: 'last day of this month')->endOf('day');
        } else {
            $start_at = Date::parse('first day of this month')->startOf('day');
            $end_at = Date::parse('last day of this month')->endOf('day');
        }

        $salespeople = Salesperson::get()->pluck('nama_sales', 'id')->prepend("Pilih Sales", '');
        $querySalesOrders = Order::query()
            ->with([
                'salesperson', 'order_details',
                'tagihan', 'pembayarans',
                'invoices', 'invoices.invoice_details',
            ])
            ->whereBetween('date', [$start_at, $end_at]);

        if ($salesperson_id = $request->input('salesperson_id')) {
            $querySalesOrders->where('salesperson_id', $salesperson_id);
        }

        $orders = $querySalesOrders->get();

        return view('admin.finishingOrders.dashboard', compact('salespeople', 'start_at', 'end_at', 'orders'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('production_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::get();

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        if ($inputGroupIds = $request->old('finishing_group_ids')) {
            $productionOrder = ProductionOrder::findOrFail($request->old('production_order_id'));
            $group_ids = explode(', ', $inputGroupIds);

            $productionOrder->load([
                'production_order_details' => function($query) use ($group_ids) {
                    $query->whereIn('group', $group_ids);
                },
            ]);
            
            $finishingOrder = new FinishingOrder([
                'date' => $productionOrder->date,
                'total' => $productionOrder->production_order_details->sum('ongkos_total'),
                'type' => 'finishing',
            ]);

            $finishing_order_details = $productionOrder->production_order_details->map(function($item) {
                $finishing_order_detail = new FinishingOrderDetail([
                    'order_qty' => $item->order_qty,
                    'prod_qty' => $item->prod_qty,
                    'ongkos_satuan' => $item->ongkos_satuan,
                    'ongkos_total' => $item->ongkos_total,
                    'product_id' => $item->product_id,
                ]);

                return $finishing_order_detail;
            });

            $finishingOrder->finishing_order_details = $finishing_order_details;

            return view('admin.finishingOrders.create', compact('productionpeople', 'products', 'categories', 'finishingOrder'));
        }

        return view('admin.finishingOrders.create', compact('productionpeople', 'products', 'categories'));
    }

    public function store(StoreFinishingOrderRequest $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'productionperson_id' => 'required|exists:productionpeople,id',
            'products' => 'required|array|min:1',
            // 'bahans' => 'required|array|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $finishingOrder = new FinishingOrder();

            $finishingOrder->forceFill([
                'productionperson_id' => $request->productionperson_id,
                'date' => $request->date,
                'total' => $request->total ?: 0,
                'type' => $request->type,
                'po_number' => FinishingOrder::generateNoPO(),
                'no_spk' => FinishingOrder::generateNoSPK(),
                'no_kwitansi' => FinishingOrder::generateNoKwitansi(),
                'created_by_id' => $user->id,
            ])->save();

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($finishingOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $prod = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'finishing_order_id' => $finishingOrder->id,
                    'order_qty' => $qty,
                    'prod_qty' => $prod,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                ];
            });

            $finishingOrder->finishing_order_details()->createMany($order_details->all());

            DB::commit();

            Alert::success('Success', 'Finishing Order berhasil di simpan');

            return redirect()->route('admin.finishing-orders.edit', $finishingOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrder->load('productionperson', 'created_by', 'finishing_order_details');

        $productionpeople = Productionperson::get();

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.finishingOrders.edit', compact('finishingOrder', 'productionpeople', 'products', 'categories'));
    }

    public function update(UpdateFinishingOrderRequest $request, FinishingOrder $finishingOrder)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'productionperson_id' => 'required|exists:productionpeople,id',
            'products' => 'required|array|min:1',
            // 'bahans' => 'required|array|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            $finishingOrder->forceFill([
                'productionperson_id' => $request->productionperson_id,
                'date' => $request->date,
                'total' => $request->total ?: 0,
                'type' => $request->type,
                'created_by_id' => $user->id,
            ])->save();

            $order_details = Product::whereIn('id', array_keys($request->products))->get()->map(function($item) use ($finishingOrder, $request) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $prod = (int) $request->products[$item->id]['prod'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;

                return [
                    'product_id' => $item->id,
                    'finishing_order_id' => $finishingOrder->id,
                    'order_qty' => $qty,
                    'prod_qty' => $prod,
                    'ongkos_satuan' => $price,
                    'ongkos_total' => $price * $qty,
                ];
            });

            foreach ($order_details as $order_detail) {
                $exists = $finishingOrder->finishing_order_details()->where('product_id', $order_detail['product_id'])->first() ?: new FinishingOrderDetail();

                $exists->forceFill($order_detail)->save();
            }

            // Delete items if removed
            $finishingOrder->finishing_order_details()
                ->whereNotIn('product_id', $order_details->pluck('product_id'))
                ->forceDelete();

            DB::commit();

            Alert::success('Success', 'Finishing Order berhasil di simpan');

            return redirect()->route('admin.finishing-orders.edit', $finishingOrder->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrder->load([
            'productionperson', 'created_by',
            'finishing_order_details', 'finishing_order_details.product',
        ]);

        switch (request('print')) {
            case 'spk':
                return view('admin.finishingOrders.prints.perintah-kerja', compact('finishingOrder'));
            case 'kwitansi':
                return view('admin.finishingOrders.prints.kwitansi', compact('finishingOrder'));
        }

        $categories = Category::whereIn('slug', ['buku', 'bahan'])->get();
        $products = Product::with(['media', 'category'])->get();

        return view('admin.finishingOrders.show', compact('finishingOrder', 'products', 'categories'));
    }

    public function destroy(FinishingOrder $finishingOrder)
    {
        abort_if(Gate::denies('production_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishingOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinishingOrderRequest $request)
    {
        FinishingOrder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
