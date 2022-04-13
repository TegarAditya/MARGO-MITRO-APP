<?php

namespace App\Http\Requests;

use App\Models\TagihanMovement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTagihanMovementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tagihan_movement_edit');
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
