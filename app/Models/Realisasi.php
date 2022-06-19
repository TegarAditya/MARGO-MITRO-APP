<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Realisasi extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'production_order_id',
        'no_realisasi',
        'date',
        'nominal',
    ];

    public function production_order()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function realisasi_details()
    {
        return $this->hasMany(RealisasiDetail::class);
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function generateNoRealisasi() {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'RL'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }
}
