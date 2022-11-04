<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPreorderRequest;
use App\Http\Requests\StorePreorderRequest;
use App\Http\Requests\UpdatePreorderRequest;
use App\Models\Preorder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PreorderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('preorder_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Preorder::query()->select(sprintf('%s.*', (new Preorder())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'preorder_show';
                $editGate = 'preorder_edit';
                $deleteGate = 'preorder_delete';
                $crudRoutePart = 'preorders';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('no_preorder', function ($row) {
                return $row->no_preorder ? $row->no_preorder : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.preorders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('preorder_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.preorders.create');
    }

    public function store(StorePreorderRequest $request)
    {
        $preorder = Preorder::create($request->all());

        return redirect()->route('admin.preorders.index');
    }

    public function edit(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.preorders.edit', compact('preorder'));
    }

    public function update(UpdatePreorderRequest $request, Preorder $preorder)
    {
        $preorder->update($request->all());

        return redirect()->route('admin.preorders.index');
    }

    public function show(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.preorders.show', compact('preorder'));
    }

    public function destroy(Preorder $preorder)
    {
        abort_if(Gate::denies('preorder_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $preorder->delete();

        return back();
    }

    public function massDestroy(MassDestroyPreorderRequest $request)
    {
        Preorder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
