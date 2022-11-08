<?php

namespace App\Http\Requests;

use App\Models\Preorder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePreorderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('preorder_create');
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
