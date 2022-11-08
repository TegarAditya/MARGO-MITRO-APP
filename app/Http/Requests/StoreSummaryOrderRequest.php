<?php

namespace App\Http\Requests;

use App\Models\SummaryOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSummaryOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('summary_order_create');
    }

    public function rules()
    {
        return [];
    }
}
