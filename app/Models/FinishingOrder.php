<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class FinishingOrder extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'po_number',
        'no_spk',
        'no_kwitansi',
        'productionperson_id',
        'date',
        'total',
        'type',
        'created_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'total' => 'double',
    ];

    public function productionperson()
    {
        return $this->belongsTo(Productionperson::class, 'productionperson_id');
    }

    public function finishing_order_details()
    {
        return $this->hasMany(FinishingOrderDetail::class);
    }

    public function realisasis()
    {
        return $this->hasMany(Realisasi::class);
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function generateNoPO()
    {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'PO'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }

    public static function generateNoSPK()
    {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'SPK'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }

    public static function generateNoKwitansi()
    {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'PO-KWI'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }
}
