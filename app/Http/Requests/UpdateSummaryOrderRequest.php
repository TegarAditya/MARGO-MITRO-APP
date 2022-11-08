<?php

namespace App\Http\Requests;

use App\Models\SummaryOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSummaryOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('summary_order_edit');
    }

    public function rules()
    {
        return [];
    }
}
