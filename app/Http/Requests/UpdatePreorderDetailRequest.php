<?php

namespace App\Http\Requests;

use App\Models\PreorderDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePreorderDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('preorder_detail_edit');
    }

    public function rules()
    {
        return [
            'preorder_detail_id' => [
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
        ];
    }
}
