<?php

namespace App\Http\Requests;

use App\Models\CustomPrice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCustomPriceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('custom_price_create');
    }

    public function rules()
    {
        return [
            'nama' => [
                'string',
                'required',
            ],
            'sales_id' => [
                'required',
                'integer',
            ],
            'kategori_id' => [
                'required',
                'integer',
            ],
            'harga' => [
                'required',
            ],
        ];
    }
}
