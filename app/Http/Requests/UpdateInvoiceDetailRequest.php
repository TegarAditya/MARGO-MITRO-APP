<?php

namespace App\Http\Requests;

use App\Models\InvoiceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInvoiceDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('invoice_detail_edit');
    }

    public function rules()
    {
        return [
            'invoice_id' => [
                'required',
                'integer',
            ],
            'product_id' => [
                'required',
                'integer',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'total' => [
                'required',
            ],
        ];
    }
}
