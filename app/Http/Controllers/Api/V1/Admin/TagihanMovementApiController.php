<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TagihanMovementResource;
use App\Models\TagihanMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagihanMovementApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tagihan_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagihanMovementResource(TagihanMovement::all());
    }

    public function show(TagihanMovement $tagihanMovement)
    {
        abort_if(Gate::denies('tagihan_movement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagihanMovementResource($tagihanMovement);
    }
}
