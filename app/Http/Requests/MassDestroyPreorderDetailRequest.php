<?php

namespace App\Http\Requests;

use App\Models\PreorderDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPreorderDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('preorder_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:preorder_details,id',
        ];
    }
}
