<?php

namespace App\Http\Requests;

use App\Models\TagihanMovement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTagihanMovementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tagihan_movement_create');
    }

    public function rules()
    {
        return [
            'reference' => [
                'string',
                'required',
            ],
            'nominal' => [
                'required',
            ],
        ];
    }
}
