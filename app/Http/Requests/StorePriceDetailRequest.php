<?php

namespace App\Http\Requests;

use App\Models\PriceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

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
                Rule::unique('price_details')->where(function ($query) {
                    return $query->where(
                        [
                            ["sales_id", "=", $this->sales_id],
                            ["price_id", "=", $this->price_id]
                        ]
                    );
                })
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
