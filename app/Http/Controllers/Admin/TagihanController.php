<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTagihanRequest;
use App\Http\Requests\StoreTagihanRequest;
use App\Http\Requests\UpdateTagihanRequest;
use App\Models\Order;
use App\Models\Salesperson;
use App\Models\Tagihan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tagihan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Tagihan::with(['order', 'salesperson'])->select(sprintf('%s.*', (new Tagihan())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'tagihan_show';
                $editGate = 'tagihan_edit';
                $deleteGate = 'tagihan_delete_hidden'; // 'tagihan_delete';
                $crudRoutePart = 'tagihans';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('order_no_order', function ($row) {
                return $row->order ? $row->order->no_order : '';
            });

            $table->editColumn('saldo', function ($row) {
                return $row->saldo ? $row->saldo : '';
            });
            $table->addColumn('salesperson_name', function ($row) {
                return $row->salesperson ? $row->salesperson->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order', 'salesperson']);

            return $table->make(true);
        }

        return view('admin.tagihans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('tagihan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('no_order', 'id')->prepend(trans('global.pleaseSelect'), '');

        $salespeople = Salesperson::get()->pluck('nama_sales', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tagihans.create', compact('orders', 'salespeople'));
    }

    public function store(StoreTagihanRequest $request)
    {
        $tagihan = Tagihan::create($request->all());

        return redirect()->route('admin.tagihans.index');
    }

    public function edit(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('no_order', 'id')->prepend(trans('global.pleaseSelect'), '');

        $salespeople = Salesperson::get()->pluck('nama_sales', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tagihan->load('order', 'salesperson');

        return view('admin.tagihans.edit', compact('orders', 'salespeople', 'tagihan'));
    }

    public function update(UpdateTagihanRequest $request, Tagihan $tagihan)
    {
        $tagihan->update($request->all());

        return redirect()->route('admin.tagihans.index');
    }

    public function show(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan->load('order', 'salesperson');

        return view('admin.tagihans.show', compact('tagihan'));
    }

    public function destroy(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan->delete();

        return back();
    }

    public function massDestroy(MassDestroyTagihanRequest $request)
    {
        Tagihan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
