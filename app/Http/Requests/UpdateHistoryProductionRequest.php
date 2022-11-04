<?php

namespace App\Http\Requests;

use App\Models\HistoryProduction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHistoryProductionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('history_production_edit');
    }

    public function rules()
    {
        return [];
    }
}
