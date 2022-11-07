<?php

namespace App\Http\Requests;

use App\Models\Pembayaran;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePembayaranGeneralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('pembayaran_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no_kwitansi' => [
                'string',
                'nullable',
            ],
            'sales_id' => [
                'required',
            ],
            'nominal' => [
                'required',
            ],
            'bayar' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'tanggal' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
