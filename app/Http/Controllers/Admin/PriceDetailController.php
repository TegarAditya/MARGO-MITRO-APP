<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPriceDetailRequest;
use App\Http\Requests\StorePriceDetailRequest;
use App\Http\Requests\UpdatePriceDetailRequest;
use App\Models\Price;
use App\Models\PriceDetail;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PriceDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('price_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PriceDetail::with(['sales', 'price'])->select(sprintf('%s.*', (new PriceDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'price_detail_show';
                $editGate = 'price_detail_edit';
                $deleteGate = 'price_detail_delete';
                $crudRoutePart = 'price-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('sales_name', function ($row) {
                return $row->sales ? $row->sales->name : '';
            });

            $table->addColumn('price_name', function ($row) {
                return $row->price ? $row->price->nama_harga : '';
            });

            $table->editColumn('diskon', function ($row) {
                return $row->diskon ? $row->diskon .' %' : '';
            });
            $table->editColumn('custom_price', function ($row) {
                return $row->custom_price ? $row->custom_price : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'sales', 'price']);

            return $table->make(true);
        }

        return view('admin.priceDetails.index');
    }

    public function create()
    {
        abort_if(Gate::denies('price_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::get()->pluck('nama_sales', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prices = Price::all();

        return view('admin.priceDetails.create', compact('prices', 'sales'));
    }

    public function store(StorePriceDetailRequest $request)
    {
        $priceDetail = PriceDetail::create($request->all());

        return redirect()->route('admin.price-details.index');
    }

    public function edit(PriceDetail $priceDetail)
    {
        abort_if(Gate::denies('price_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::get()->pluck('nama_sales', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prices = Price::all();

        $priceDetail->load('sales', 'price');

        return view('admin.priceDetails.edit', compact('priceDetail', 'prices', 'sales'));
    }

    public function update(UpdatePriceDetailRequest $request, PriceDetail $priceDetail)
    {
        $priceDetail->update($request->all());

        return redirect()->route('admin.price-details.index');
    }

    public function show(PriceDetail $priceDetail)
    {
        abort_if(Gate::denies('price_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $priceDetail->load('sales', 'price');

        return view('admin.priceDetails.show', compact('priceDetail'));
    }

    public function destroy(PriceDetail $priceDetail)
    {
        abort_if(Gate::denies('price_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $priceDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroyPriceDetailRequest $request)
    {
        PriceDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
