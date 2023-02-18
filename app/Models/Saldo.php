<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Saldo extends Model
{
    use Auditable;
    use HasFactory;

    public $table = 'saldos';

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'kode',
        'periode',
        'salesperson_id',
        'start_date',
        'end_date',
        'saldo_awal',
        'saldo_akhir',
        'tagihan',
        'retur',
        'bayar',
        'diskon',
        'created_at',
        'updated_at',
    ];

    public function sales()
    {
        return $this->belongsTo(Salesperson::class, 'salesperson_id');
    }
}
