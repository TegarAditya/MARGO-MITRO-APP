<?php

namespace App\Http\Requests;

use App\Models\Preorder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePreorderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('preorder_edit');
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
