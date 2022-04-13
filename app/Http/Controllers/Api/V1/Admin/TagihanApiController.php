<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagihanRequest;
use App\Http\Requests\UpdateTagihanRequest;
use App\Http\Resources\Admin\TagihanResource;
use App\Models\Tagihan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagihanApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tagihan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagihanResource(Tagihan::with(['order', 'salesperson'])->get());
    }

    public function store(StoreTagihanRequest $request)
    {
        $tagihan = Tagihan::create($request->all());

        return (new TagihanResource($tagihan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagihanResource($tagihan->load(['order', 'salesperson']));
    }

    public function update(UpdateTagihanRequest $request, Tagihan $tagihan)
    {
        $tagihan->update($request->all());

        return (new TagihanResource($tagihan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
