<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Pembayaran extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'pembayarans';

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_kwitansi',
        'tagihan_id',
        'order_id',
        'nominal',
        'diskon',
        'bayar',
        'tanggal',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'nominal' => 'double',
        'diskon' => 'double',
        'bayar' => 'double',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function getTanggalAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function generateNoKwitansi()
    {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'KWI'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }
}
