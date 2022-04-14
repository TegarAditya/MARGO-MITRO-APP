<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySalespersonRequest;
use App\Http\Requests\StoreSalespersonRequest;
use App\Http\Requests\UpdateSalespersonRequest;
use App\Models\City;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SalespersonController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('salesperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Salesperson::with(['area_pemasarans'])->select(sprintf('%s.*', (new Salesperson())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'salesperson_show';
                $editGate = 'salesperson_edit';
                $deleteGate = 'salesperson_delete';
                $crudRoutePart = 'salespeople';

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
            $table->editColumn('area_pemasaran', function ($row) {
                $labels = [];
                foreach ($row->area_pemasarans as $area_pemasaran) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $area_pemasaran->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'area_pemasaran']);

            return $table->make(true);
        }

        return view('admin.salespeople.index');
    }

    public function create()
    {
        abort_if(Gate::denies('salesperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area_pemasarans = City::pluck('name', 'id');

        return view('admin.salespeople.create', compact('area_pemasarans'));
    }

    public function store(StoreSalespersonRequest $request)
    {
        $salesperson = Salesperson::create($request->all());
        $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));

        return redirect()->route('admin.salespeople.index');
    }

    public function edit(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area_pemasarans = City::pluck('name', 'id');

        $salesperson->load('area_pemasarans');

        return view('admin.salespeople.edit', compact('area_pemasarans', 'salesperson'));
    }

    public function update(UpdateSalespersonRequest $request, Salesperson $salesperson)
    {
        $salesperson->update($request->all());
        $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));

        return redirect()->route('admin.salespeople.index');
    }

    public function show(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->load('area_pemasarans');

        return view('admin.salespeople.show', compact('salesperson'));
    }

    public function destroy(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->delete();

        return back();
    }

    public function massDestroy(MassDestroySalespersonRequest $request)
    {
        Salesperson::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
