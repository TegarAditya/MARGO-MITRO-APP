<?php

namespace App\Http\Requests;

use App\Models\CustomPrice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCustomPriceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('custom_price_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:custom_prices,id',
        ];
    }
}
