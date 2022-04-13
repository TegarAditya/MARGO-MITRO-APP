<?php

namespace App\Http\Requests;

use App\Models\TagihanMovement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTagihanMovementRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tagihan_movement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tagihan_movements,id',
        ];
    }
}
