<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceDetailRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceDetailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('invoice_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoiceDetails = InvoiceDetail::with(['invoice', 'product'])->get();

        return view('frontend.invoiceDetails.index', compact('invoiceDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('invoice_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoices = Invoice::pluck('no_invoice', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.invoiceDetails.create', compact('invoices', 'products'));
    }

    public function store(StoreInvoiceDetailRequest $request)
    {
        $invoiceDetail = InvoiceDetail::create($request->all());

        return redirect()->route('frontend.invoice-details.index');
    }
}
