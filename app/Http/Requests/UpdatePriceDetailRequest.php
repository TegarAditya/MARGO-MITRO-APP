<?php

namespace App\Http\Requests;

use App\Models\PriceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePriceDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('price_detail_edit');
    }

    public function rules()
    {
        return [
            'sales_id' => [
                'required',
                'integer',
            ],
            'price_id' => [
                'required',
                'integer',
            ],
            'diskon' => [
                'numeric',
                'required',
                'min:0',
                'max:100',
            ],
            'custom_price' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
        ];
    }
}
