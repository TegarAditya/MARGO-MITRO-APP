<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCustomPriceRequest;
use App\Http\Requests\StoreCustomPriceRequest;
use App\Http\Requests\UpdateCustomPriceRequest;
use App\Models\Category;
use App\Models\CustomPrice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CustomPriceController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('custom_price_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CustomPrice::with(['kategori'])->select(sprintf('%s.*', (new CustomPrice())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'custom_price_show';
                $editGate = 'custom_price_edit';
                $deleteGate = 'custom_price_delete';
                $crudRoutePart = 'custom-prices';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->addColumn('kategori_name', function ($row) {
                return $row->kategori ? $row->kategori->name : '';
            });

            $table->editColumn('kategori.type', function ($row) {
                return $row->kategori ? (is_string($row->kategori) ? $row->kategori : $row->kategori->type) : '';
            });
            $table->editColumn('harga', function ($row) {
                return $row->harga ? $row->harga : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'kategori']);

            return $table->make(true);
        }

        return view('admin.customPrices.index');
    }

    public function create()
    {
        abort_if(Gate::denies('custom_price_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kategoris = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.customPrices.create', compact('kategoris'));
    }

    public function store(StoreCustomPriceRequest $request)
    {
        $customPrice = CustomPrice::create($request->all());

        return redirect()->route('admin.custom-prices.index');
    }

    public function edit(CustomPrice $customPrice)
    {
        abort_if(Gate::denies('custom_price_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kategoris = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customPrice->load('kategori');

        return view('admin.customPrices.edit', compact('customPrice', 'kategoris'));
    }

    public function update(UpdateCustomPriceRequest $request, CustomPrice $customPrice)
    {
        $customPrice->update($request->all());

        return redirect()->route('admin.custom-prices.index');
    }

    public function show(CustomPrice $customPrice)
    {
        abort_if(Gate::denies('custom_price_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customPrice->load('kategori');

        return view('admin.customPrices.show', compact('customPrice'));
    }

    public function destroy(CustomPrice $customPrice)
    {
        abort_if(Gate::denies('custom_price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customPrice->delete();

        return back();
    }

    public function massDestroy(MassDestroyCustomPriceRequest $request)
    {
        CustomPrice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
