<?php

namespace App\Http\Requests;

use App\Models\HistoryProduction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHistoryProductionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('history_production_create');
    }

    public function rules()
    {
        return [];
    }
}
