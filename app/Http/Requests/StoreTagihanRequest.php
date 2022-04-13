<?php

namespace App\Http\Requests;

use App\Models\Tagihan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTagihanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tagihan_create');
    }

    public function rules()
    {
        return [
            'order_id' => [
                'required',
                'integer',
            ],
            'saldo' => [
                'required',
            ],
        ];
    }
}
