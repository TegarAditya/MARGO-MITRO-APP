<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductionpersonRequest;
use App\Http\Requests\StoreProductionpersonRequest;
use App\Http\Requests\UpdateProductionpersonRequest;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionpersonController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('productionperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionpeople = Productionperson::all();

        return view('frontend.productionpeople.index', compact('productionpeople'));
    }

    public function create()
    {
        abort_if(Gate::denies('productionperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.productionpeople.create');
    }

    public function store(StoreProductionpersonRequest $request)
    {
        $productionperson = Productionperson::create($request->all());

        return redirect()->route('frontend.productionpeople.index');
    }

    public function edit(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.productionpeople.edit', compact('productionperson'));
    }

    public function update(UpdateProductionpersonRequest $request, Productionperson $productionperson)
    {
        $productionperson->update($request->all());

        return redirect()->route('frontend.productionpeople.index');
    }

    public function show(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.productionpeople.show', compact('productionperson'));
    }

    public function destroy(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionperson->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductionpersonRequest $request)
    {
        Productionperson::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
