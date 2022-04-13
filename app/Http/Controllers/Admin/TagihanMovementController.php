<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanMovement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TagihanMovementController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tagihan_movement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TagihanMovement::query()->select(sprintf('%s.*', (new TagihanMovement())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'tagihan_movement_show';
                $editGate = 'tagihan_movement_edit';
                $deleteGate = 'tagihan_movement_delete';
                $crudRoutePart = 'tagihan-movements';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? TagihanMovement::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('nominal', function ($row) {
                return $row->nominal ? $row->nominal : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.tagihanMovements.index');
    }

    public function show(TagihanMovement $tagihanMovement)
    {
        abort_if(Gate::denies('tagihan_movement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tagihanMovements.show', compact('tagihanMovement'));
    }
}
