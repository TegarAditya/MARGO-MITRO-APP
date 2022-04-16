<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionpersonRequest;
use App\Http\Requests\UpdateProductionpersonRequest;
use App\Http\Resources\Admin\ProductionpersonResource;
use App\Models\Productionperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionpersonApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('productionperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductionpersonResource(Productionperson::all());
    }

    public function store(StoreProductionpersonRequest $request)
    {
        $productionperson = Productionperson::create($request->all());

        return (new ProductionpersonResource($productionperson))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductionpersonResource($productionperson);
    }

    public function update(UpdateProductionpersonRequest $request, Productionperson $productionperson)
    {
        $productionperson->update($request->all());

        return (new ProductionpersonResource($productionperson))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Productionperson $productionperson)
    {
        abort_if(Gate::denies('productionperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productionperson->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
