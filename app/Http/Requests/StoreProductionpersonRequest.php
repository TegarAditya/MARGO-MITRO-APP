<?php

namespace App\Http\Requests;

use App\Models\Productionperson;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductionpersonRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('productionperson_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'type' => [
                'required',
            ],
            'contact' => [
                'string',
                'nullable',
            ],
        ];
    }
}
