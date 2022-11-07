<?php

namespace App\Http\Requests;

use App\Models\FinishingOrderDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFinishingOrderDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('production_order_detail_create');
    }

    public function rules()
    {
        return [
            'order_qty' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'prod_qty' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'ongkos_satuan' => [
                'required',
            ],
            'ongkos_total' => [
                'required',
            ],
        ];
    }
}
