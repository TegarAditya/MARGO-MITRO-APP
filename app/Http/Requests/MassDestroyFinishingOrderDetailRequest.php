<?php

namespace App\Http\Requests;

use App\Models\FinishingOrderDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFinishingOrderDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('production_order_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:finishing_order_details,id',
        ];
    }
}
