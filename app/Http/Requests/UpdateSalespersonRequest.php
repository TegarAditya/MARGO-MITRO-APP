<?php

namespace App\Http\Requests;

use App\Models\Salesperson;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSalespersonRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('salesperson_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
