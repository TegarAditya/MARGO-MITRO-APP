<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceDetailRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class InvoiceDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('invoice_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = InvoiceDetail::with(['invoice', 'product'])->select(sprintf('%s.*', (new InvoiceDetail())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'invoice_detail_show';
                $editGate = 'invoice_detail_edit';
                $deleteGate = 'invoice_detail_delete';
                $crudRoutePart = 'invoice-details';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->addColumn('invoice_no_invoice', function ($row) {
                return $row->invoice ? $row->invoice->no_invoice : '';
            });

            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('quantity', function ($row) {
                return $row->quantity ? $row->quantity : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'invoice', 'product']);

            return $table->make(true);
        }

        return view('admin.invoiceDetails.index');
    }

    public function create()
    {
        abort_if(Gate::denies('invoice_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoices = Invoice::pluck('no_invoice', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.invoiceDetails.create', compact('invoices', 'products'));
    }

    public function store(StoreInvoiceDetailRequest $request)
    {
        $invoiceDetail = InvoiceDetail::create($request->all());

        return redirect()->route('admin.invoice-details.index');
    }
}
