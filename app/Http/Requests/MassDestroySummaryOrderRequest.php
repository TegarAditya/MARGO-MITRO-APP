<?php

namespace App\Http\Requests;

use App\Models\SummaryOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySummaryOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('summary_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:summary_orders,id',
        ];
    }
}
