<?php

namespace App\Http\Requests;

use App\Models\CustomPrice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCustomPriceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('custom_price_edit');
    }

    public function rules()
    {
        return [
            'nama' => [
                'string',
                'required',
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
