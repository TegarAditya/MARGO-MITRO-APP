<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionpersonRequest;
use App\Http\Requests\StoreProductionpersonRequest;
use App\Http\Requests\UpdateProductionpersonRequest;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Excel;
use App\Imports\ProductionpersonImport;
use Alert;

class ProductionpersonController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('productionperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Productionperson::query()->select(sprintf('%s.*', (new Productionperson())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'productionperson_show';
                $editGate = 'productionperson_edit';
                $deleteGate = 'productionperson_delete';
                $crudRoutePart = 'productionpeople';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Productionperson::TYPE_SELECT[$row->type] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.productionpeople.index');
    }

    public function create()
    {
        abort_if(Gate::denies('productionperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productionpeople.create');
    }

    public function store(StoreProductionpersonRequest $request)
    {
        $productionperson = Productionperson::create($request->all());

        return redirect()->route('admin.productionpeople.index');
    }

    public function edit(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productionpeople.edit', compact('productionperson'));
    }

    public function update(UpdateProductionpersonRequest $request, Productionperson $productionperson)
    {
        $productionperson->update($request->all());

        return redirect()->route('admin.productionpeople.index');
    }

    public function show(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productionpeople.show', compact('productionperson'));
    }

    public function destroy(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionperson->delete();

        Alert::success('Success', 'Orang produksi berhasil di hapus');

        return back();
    }

    public function massDestroy(MassDestroyProductionpersonRequest $request)
    {
        Productionperson::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file');
        $request->validate([
            'import_file' => 'mimes:csv,txt,xls,xlsx',
        ]);

        Excel::import(new ProductionpersonImport(), $file);

        Alert::success('Success', 'Orang produksi berhasil di import');
        return redirect()->back();
    }
}
