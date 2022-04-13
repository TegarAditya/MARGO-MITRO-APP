<?php

namespace App\Http\Requests;

use App\Models\InvoiceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInvoiceDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('invoice_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:invoice_details,id',
        ];
    }
}
