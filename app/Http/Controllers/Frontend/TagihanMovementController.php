<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TagihanMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagihanMovementController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tagihan_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihanMovements = TagihanMovement::all();

        return view('frontend.tagihanMovements.index', compact('tagihanMovements'));
    }

    public function show(TagihanMovement $tagihanMovement)
    {
        abort_if(Gate::denies('tagihan_movement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tagihanMovements.show', compact('tagihanMovement'));
    }
}
