<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyHistoryProductionRequest;
use App\Models\HistoryProduction;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class HistoryProductionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('history_production_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = HistoryProduction::with(['reference', 'summary_order', 'product'])->select(sprintf('%s.*', (new HistoryProduction())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'history_production_show';
                $editGate = 'history_production_edit';
                $deleteGate = 'history_production_delete';
                $crudRoutePart = 'history-productions';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('reference_no_preorder', function ($row) {
                return $row->reference ? $row->reference->no_preorder : '';
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? HistoryProduction::TYPE_SELECT[$row->type] : '';
            });
            $table->addColumn('summary_order_type', function ($row) {
                return $row->summary_order ? $row->summary_order->type : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'reference', 'summary_order', 'product']);

            return $table->make(true);
        }

        return view('admin.historyProductions.index');
    }

    public function show(HistoryProduction $historyProduction)
    {
        abort_if(Gate::denies('history_production_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $historyProduction->load('reference', 'summary_order', 'product');

        return view('admin.historyProductions.show', compact('historyProduction'));
    }

    public function destroy(HistoryProduction $historyProduction)
    {
        abort_if(Gate::denies('history_production_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $historyProduction->delete();

        return back();
    }

    public function massDestroy(MassDestroyHistoryProductionRequest $request)
    {
        HistoryProduction::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
