<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCustomPriceRequest;
use App\Http\Requests\StoreCustomPriceRequest;
use App\Http\Requests\UpdateCustomPriceRequest;
use App\Models\Category;
use App\Models\CustomPrice;
use App\Models\Price;
use App\Models\PriceDetail;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Excel;
use App\Imports\CustomPriceImport;
use Alert;

class CustomPriceController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('custom_price_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CustomPrice::with(['kategori', 'sales'])->select(sprintf('%s.*', (new CustomPrice())->table));

            if (!empty($request->sales)) {
                $query->where('sales_id', $request->sales);
            }

            if (!empty($request->halaman)) {
                $query->where('kategori_id', $request->halaman);
            }

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
            $table->addColumn('sales', function ($row) {
                return $row->sales ? $row->sales->name: '';
            });
            $table->addColumn('kategori_name', function ($row) {
                return $row->kategori ? (Category::TYPE_SELECT[$row->kategori->type]. ' ' .$row->kategori->name) : '';
            });

            $table->editColumn('harga', function ($row) {
                return $row->harga ? $row->harga : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'kategori']);

            return $table->make(true);
        }

        $sales = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kategoris = Category::whereIn('type', ['halaman'])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.customPrices.index', compact('kategoris', 'sales'));
    }

    public function create()
    {
        abort_if(Gate::denies('custom_price_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kategoris = Category::whereIn('type', ['halaman'])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.customPrices.create', compact('kategoris', 'sales'));
    }

    public function store(StoreCustomPriceRequest $request)
    {
        $customPrice = CustomPrice::create($request->all());

        return redirect()->route('admin.custom-prices.index');
    }

    public function edit(CustomPrice $customPrice)
    {
        abort_if(Gate::denies('custom_price_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kategoris = Category::whereIn('type', ['halaman'])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customPrice->load('kategori', 'sales');

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

        $sales_id = $customPrice->sales_id;

        $harga = CustomPrice::where('sales_id', $sales_id)->get();

        $customPrice->load('kategori', 'sales');

        return view('admin.customPrices.show', compact('customPrice', 'harga'));
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

    public function select(Request $request)
    {
        $sales = $request->sales;
        // $customprices = CustomPrice::where('sales_id', $sales)->get()->pluck('nama_harga', 'id');
        $customprices = Price::all()->pluck('nama_harga', 'id');
        return response()->json($customprices);
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file');
        $request->validate([
            'import_file' => 'mimes:csv,txt,xls,xlsx',
        ]);

        Excel::import(new CustomPriceImport(), $file);

        Alert::success('Success', 'Custom Price berhasil di import');
        return redirect()->back();
    }
}
