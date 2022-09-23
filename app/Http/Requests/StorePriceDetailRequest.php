<?php

namespace App\Http\Requests;

use App\Models\PriceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePriceDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('price_detail_create');
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
            ],
        ];
    }
}
