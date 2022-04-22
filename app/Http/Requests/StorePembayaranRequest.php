<?php

namespace App\Http\Requests;

use App\Models\Pembayaran;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePembayaranRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pembayaran_create');
    }

    public function rules()
    {
        return [
            'no_kwitansi' => [
                'string',
                'nullable',
            ],
            'tagihan_id' => [
                'required',
                'integer',
                'exists:tagihans,id'
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
