<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySalespersonRequest;
use App\Http\Requests\StoreSalespersonRequest;
use App\Http\Requests\UpdateSalespersonRequest;
use App\Models\City;
use App\Models\Salesperson;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SalespersonController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('salesperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::with(['area_pemasarans', 'media'])->get();

        return view('frontend.salespeople.index', compact('salespeople'));
    }

    public function create()
    {
        abort_if(Gate::denies('salesperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area_pemasarans = City::pluck('name', 'id');

        return view('frontend.salespeople.create', compact('area_pemasarans'));
    }

    public function store(StoreSalespersonRequest $request)
    {
        $salesperson = Salesperson::create($request->all());
        $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));
        if ($request->input('foto', false)) {
            $salesperson->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $salesperson->id]);
        }

        return redirect()->route('frontend.salespeople.index');
    }

    public function edit(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $area_pemasarans = City::pluck('name', 'id');

        $salesperson->load('area_pemasarans');

        return view('frontend.salespeople.edit', compact('area_pemasarans', 'salesperson'));
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

        return redirect()->route('frontend.salespeople.index');
    }

    public function show(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->load('area_pemasarans');

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

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('salesperson_create') && Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Salesperson();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
