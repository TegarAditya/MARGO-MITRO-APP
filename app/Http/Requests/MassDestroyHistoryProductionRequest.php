<?php

namespace App\Http\Requests;

use App\Models\HistoryProduction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHistoryProductionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('history_production_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:history_productions,id',
        ];
    }
}
