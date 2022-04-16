<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSalespersonRequest;
use App\Http\Requests\UpdateSalespersonRequest;
use App\Http\Resources\Admin\SalespersonResource;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SalespersonApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('salesperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SalespersonResource(Salesperson::with(['area_pemasarans'])->get());
    }

    public function store(StoreSalespersonRequest $request)
    {
        $salesperson = Salesperson::create($request->all());
        $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));
        if ($request->input('foto', false)) {
            $salesperson->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return (new SalespersonResource($salesperson))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SalespersonResource($salesperson->load(['area_pemasarans']));
    }

    public function update(UpdateSalespersonRequest $request, Salesperson $salesperson)
    {
        $salesperson->update($request->all());
        $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));
        if ($request->input('foto', false)) {
            if (!$salesperson->foto || $request->input('foto') !== $salesperson->foto->file_name) {
                if ($salesperson->foto) {
                    $salesperson->foto->delete();
                }
                $salesperson->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }
        } elseif ($salesperson->foto) {
            $salesperson->foto->delete();
        }

        return (new SalespersonResource($salesperson))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
