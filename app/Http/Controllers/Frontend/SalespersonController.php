<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySalespersonRequest;
use App\Http\Requests\StoreSalespersonRequest;
use App\Http\Requests\UpdateSalespersonRequest;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SalespersonController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('salesperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::all();

        return view('frontend.salespeople.index', compact('salespeople'));
    }

    public function create()
    {
        abort_if(Gate::denies('salesperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.salespeople.create');
    }

    public function store(StoreSalespersonRequest $request)
    {
        $salesperson = Salesperson::create($request->all());

        return redirect()->route('frontend.salespeople.index');
    }

    public function edit(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.salespeople.edit', compact('salesperson'));
    }

    public function update(UpdateSalespersonRequest $request, Salesperson $salesperson)
    {
        $salesperson->update($request->all());

        return redirect()->route('frontend.salespeople.index');
    }

    public function show(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.salespeople.show', compact('salesperson'));
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
