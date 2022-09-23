<?php

namespace App\Http\Requests;

use App\Models\PriceDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPriceDetailRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('price_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:price_details,id',
        ];
    }
}
